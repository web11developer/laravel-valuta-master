<?php

namespace App\Http\Controllers;

use App\Cash;
use App\Currency;
use App\DTO\CashDTO;
use App\Exchangers;
use App\Http\Requests\CashRequest;
use App\Jobs\SendMessage;
use App\Jobs\Updategridview;
use App\Language;
use App\Models\Currency\Helpers\ExchangeHelper;
use App\Observers\CashObserver;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redis;


class CashController extends Controller
{

    public function __construct()
    {
        view()->share('type', 'cash');
        $this->middleware('auth');
    }

    public function show(Exchangers $exchange)
    {

        if (Auth::user()->id != $exchange->user_id) {
            if (Auth::user()->admin != 1) {
                return redirect('/');
            }
        }

        if ($exchange->parent) {
            return redirect('cash/' . $exchange->parent . '/show')
                ->with('message', 'Внимание Вы переадресованы на родителя! Нельзя обновлять курсы для обменного пункта который является дочерним!');
        }

        $cash = Cash::where('exchange_id', $exchange->id)->first();
        $rates_json = [];
        if($cash){

            $rates_json = $cash->rates_json;
        }

        //$currency = Currency::orderBy('order_number', 'asc')->get();
        $currency = ExchangeHelper::getCurrenciesByExchangeId($exchange->id);
        $exchangers = Exchangers::where('user_id', $exchange->user_id)
            ->where('parent', 0)
            ->where('id', '!=', $exchange->id)
            ->orderBy('id', 'asc')
            ->pluck('title', 'id')->toArray();
        return view('cash.create_update', compact('exchange', 'cash', 'currency', 'exchangers','rates_json'));
    }

    public function update(CashRequest $request)
    {
        $new_cash_values = $request->all();
        $date = date('Y-m-d G:i:s');
        $cash = Cash::where('exchange_id', $new_cash_values['exchange_id'])->first();
        $exchangers = Exchangers::with('cash')->where('parent', $new_cash_values['exchange_id'])->get();
        $exchange = Exchangers::with('currencies')->where('id',$new_cash_values['exchange_id'])->first();
        $currencies = $exchange->currencies;
        $manageDto = new CashDTO($date,$new_cash_values,$request,$currencies,$cash);
        if (!$cash) {

            Cash::createItem($manageDto);
        }
        else
            Cash::updateItem($manageDto);
        if (count($exchangers)) {
            $items = ExchangeHelper::getClearItems($request->get('items',[]));
            foreach ($exchangers as $item) {
                $cash = $item->cash;
                $n = $request->except('exchange_id','items','_method','_token');
                $n['exchange_id'] = $item->id;
                $n['rates_json'] = json_encode($items);
                $n['updated_at'] = $date;
                if (!$cash) {
                    DB::table('front_page_data')->insert($n);
                } else {
                    DB::table('front_page_data')->where('id',$cash->id)->update($n);
                }
            }
        }
        Redis::del('valuta:cash:1');
        $redis_key = 'old_rates:cash:'.$exchange->city;
        Redis::del($redis_key);
        $redis_key = 'old_rates:cash:home';
        Redis::del($redis_key);
        $redis_key = 'cash_rates_distance:' . $exchange->id;
        Redis::del($redis_key);
        $cash = Cash::where('exchange_id', $new_cash_values['exchange_id'])->first();
        if($cash) {
            Updategridview::dispatch($cash);

        }

        DB::table('subscribes')->where('exchange_id', '=', $new_cash_values['exchange_id'])->update(['send' => false]);

//        $emailJob = (new SendMessage('123', $new_cash_values['exchange_id']))->delay(Carbon::now()->addSeconds(3));
//        dispatch($emailJob);

        return redirect('/home')->with('message', 'Курсы обменного пункта обновлены успешно!');
    }
}
