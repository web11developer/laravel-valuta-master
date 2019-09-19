<?php

namespace App\Http\Controllers;

use App\Cash;
use App\CashRates;
use App\Cities;
use App\Currency;
use App\Exchangers;
use App\Http\Requests\ExchangersRequest;
use App\Models\Currency\Dto\ExchangeCurrencyDTO;
use App\Models\Currency\ExchangeCurrency;
use App\Models\Currency\Helpers\ExchangeHelper;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redis;
use MongoDB\BSON\Javascript;

if(version_compare(PHP_VERSION, '7.2.0', '>=')) {
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}

class ExchangersController extends Controller
{

    public function __construct()
    {
        view()->share('type', 'exchange');
        $this->middleware('auth', ['except' => ['show']]);
    }

    public function index($user)
    {
        if (Auth::user()->id != $user->id) {
            if (Auth::user()->admin != 1) {
                return redirect('/');
            }
        }

        $exchangers = Exchangers::with(['cities'])->where('user_id', $user->id)->get();
        return view('exchangers.index', compact('exchangers', 'user'));
    }

    public function show(Exchangers $exchange)
    {
        ini_set('memory_limit', '2048M');
//dd($exchange->followers);
        $cities = Cities::where('id', $exchange->city);

        $currency = $exchange->currencies->all();

        if(!$currency){
            $currency = Currency::orderBy('order_number', 'asc')->get();
        }
        $exchange_user = User::where('id', $exchange->user_id)->first();

        $cash = Cash::where('exchange_id', $exchange->id)->first();
        $cashRates = CashRates::where('exchange_id',$exchange->id)->first();
        if (!count($cash) ||  count($cashRates) < 1) {
            return redirect('/')->with('message', 'Обменный пункт не имеет данных о курсах');
        }

        $years = CashRates::select(DB::raw('year(changed_at) as changed_at'))
            ->where('exchange_id', $exchange->id)
            ->where('currency_id', $cashRates->currency_id)
            ->orderBy('changed_at', 'desc')
            ->groupBy(DB::raw('year(changed_at)'))->get();

        $cache_distanceRates = null;
       if(Auth::guest() || (!Auth::guest() && $exchange->diff_exchange == 1)) {
           $cache_distanceRates = Redis::get('cash_rates_distance:' . $exchange->id);
           if ($cache_distanceRates) {
               $cache_distanceRates = unserialize($cache_distanceRates);
           } else {
               $cache_distanceRates = DB::table('cash_rates')
                   ->select(['rate_value_buy', 'rate_value_sell', 'currency_id'])
                   ->where('exchange_id', $exchange->id)
                   ->orderBy('changed_at', 'desc')
                   ->get()
                   ->groupBy('currency_id')
                   ->map(function ($deal) {
                       return $deal->take(2);
                   })->all();
               Redis::set('cash_rates_distance:' . $exchange->id, serialize($cache_distanceRates));
           }
       }

        $years_list = [];
        foreach ($years as $y) {
            $years_list[] = $y->changed_at;
        }

        $currency_list = [];
        foreach ($currency as $c) {
            $currency_list[$c->currency_id] = $c->code;
        }

        $dataCalc = [];
        if($cash){
            $dataCalc = collect([$cash])->map(function ($model,$key) use ($exchange){
                return [
                    'exchange_id'=>  $exchange->id,
                    'rates_json'=>$model->rates_json,
                    'currencies'=>$this->getCurrencyFromRates($model->rates_json)
                ];
            });
        }
     //   dd($dataCalc);

        return view('exchangers.view',
            compact('exchange', 'cities', 'currency', 'cash', 'years_list', 'currency_list', 'exchange_user','cache_distanceRates','dataCalc'));
    }

    public function create($user)
    {


        if (Auth::user()->id != $user->id) {
            if (Auth::user()->admin != 1) {
                return redirect('/');
            }
        }

        $cities = Cities::pluck('name', 'id')->toArray();
        $openTime = [
            '' => 'Выберите...',
            Exchangers::ALL_OPEN => 'Круглосуточно',
            Exchangers::CURRENT_CLOSE => 'Сейчас закрыто',
            Exchangers::CURRENT_OPEN => 'Сейчас открыто'

        ];
//        $count_exchangers = Exchangers::count();
//
//        if(($user->max_exchangers - $count_exchangers) == 0){
//            return redirect('/exchangers/' . $user->id)->with('message', 'Невозможно создать новый обменный пункт, превышен лимит!');
//        }

        $exchangers = Exchangers::where('parent', 0)
            ->orderBy('id', 'asc')
            ->pluck('title', 'id')->toArray();

        $exchangers = ['Без родителя'] + $exchangers;
       $has_child = 0;
        $currencyCurrencies  = ExchangeHelper::getCurrencyList();
        return view('exchangers.create', compact('cities', 'exchangers', 'user','currencyCurrencies', 'openTime','has_child'));
    }

    public function store(ExchangersRequest $request)
    {

        $data = $request->except('picture', 'del_picture', 'is_visible','currency_list');
        $diff_exchange = $request->get('diff_exchange',0);
        $data['diff_exchange'] = $diff_exchange;
        $exchange = new Exchangers($data);

        if (Auth::user()->id != $exchange->user_id) {
            if (Auth::user()->admin != 1) {
                return redirect('/');
            }
        }

        $picture = "";
        if (Input::hasFile('picture')) {
            $file = Input::file('picture');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $picture = sha1($filename . time()) . '.' . $extension;
        }

        $exchange->picture = $picture;
        $exchange->is_visible = 0;

        $exchange->save();

        if (Input::hasFile('picture')) {
            $destinationPath = public_path() . '/images/exchange/' . $exchange->id . '/';
            Input::file('picture')->move($destinationPath, $picture);
        }

        $currencyDTO = new ExchangeCurrencyDTO($exchange->id,$request->get('currency_list'));
        ExchangeCurrency::create($currencyDTO);
        Redis::del('valuta:cash:1');
        return redirect('/exchangers/' . $exchange->user_id)->with('message', 'Обменный пункт успешно добавлен!');
    }

    public function edit(Exchangers $exchange)
    {

        if (Auth::user()->id != $exchange->user_id) {
            if (Auth::user()->admin != 1) {
                return redirect('/');
            }
        }

        $cities = Cities::pluck('name', 'id')->toArray();
        $openTime = [
            '' => 'Выберите...',
            Exchangers::ALL_OPEN => 'Круглосуточно',
            Exchangers::CURRENT_CLOSE => 'Сейчас закрыто',
            Exchangers::CURRENT_OPEN => 'Сейчас открыто'

        ];

        $has_child =0;
        $exchangers = Exchangers::where('parent', 0)
            ->where('id', '!=', $exchange->id)
            ->orderBy('id', 'asc')
            ->pluck('title', 'id')->toArray();
        $exchangers = ['Без родителя'] + $exchangers;

        $currencyCurrencies  = ExchangeHelper::getCurrencyList();

        $selectedCurrencies = ExchangeHelper::getCodesByExcahge($exchange->id);

        return view('exchangers.create', compact('exchange', 'cities', 'exchangers','currencyCurrencies','selectedCurrencies', 'openTime','has_child'));
    }

    public function update(ExchangersRequest $request, Exchangers $exchange)
    {
        if (Auth::user()->id != $exchange->user_id) {
            if (Auth::user()->admin != 1) {
                return redirect('/');
            }
        }

        if (Input::has('del_picture')) {
            $picture = "";
        } else {
            $picture = $exchange->picture;
        }

        if (Input::has('is_visible')) {
            $exchange->is_visible = $request->is_visible;
        }

        if (Input::has('location')) {
            $exchange->location = $request->location;
        }

        if (Input::hasFile('picture')) {
            $file = Input::file('picture');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $picture = sha1($filename . time()) . '.' . $extension;
            $destinationPath = public_path() . '/images/exchange/' . $exchange->id . '/';
            Input::file('picture')->move($destinationPath, $picture);
        }
        $exchange->picture = $picture;
        $data = $request->except('picture', 'del_picture', 'is_visible','currency_list');
        $diff_exchange = $request->get('diff_exchange',0);
        $data['diff_exchange'] = $diff_exchange;

        $exchange->update($data);

        $currencyDTO = new ExchangeCurrencyDTO($exchange->id,$request->get('currency_list'));
        ExchangeCurrency::create($currencyDTO);
        Redis::del('valuta:cash:1');
        Redis::del('valuta:cash:home');
        return redirect('/exchangers/' . $exchange->user_id)->with('message', 'Обменный пункт успешно обновлен!');
    }

    private function getCurrencyFromRates($rates_json)
    {
        $list = array_keys($rates_json);
        $data = [];
        foreach ($list as $item) {
            $l = str_replace('_buy', '', $item);
            $l = strtoupper(str_replace('_sell', '', $l));
            $data[] = $l;
        }
        return array_unique($data);
    }
}
