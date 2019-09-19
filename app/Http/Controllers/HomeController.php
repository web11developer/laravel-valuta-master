<?php

namespace App\Http\Controllers;

use App\Cash;
use App\CashRates;
use App\Cities;
use App\Currency;
use App\Exchangers;
use App\Helpers\SqlHelper;
use App\Helpers\TranslationHelper;
use App\Models\Currency\Dto\ExchangeCurrencyDTO;
use App\Models\Currency\ExchangeCurrency;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\View;

class HomeController extends Controller
{

    private function Currency()
    {
        $is_cookie = isset($_COOKIE['currency_show']);


      //  $cache_currency = Redis::get('valuta:currencies');
        $cache_currency = null;

        if ($cache_currency) {
            $currency = unserialize($cache_currency);
        } else {
            $currency = Currency::orderBy('order_number', 'asc')->get();
            Redis::set('valuta:currencies', serialize($currency));
        }

        if ($is_cookie) {
            $cookie_cur = explode(',', $_COOKIE['currency_show']);
            $currency->map(function ($item) use ($cookie_cur) {
                if (in_array($item->code, $cookie_cur)) {
                    $item->visible_bool = 1;
                } else {
                    $item->visible_bool = 0;
                }
                return $item;
            });
        }
        return $currency;
    }

    private function City()
    {
        $cache_cities = Redis::get('valuta:cities');
        if ($cache_cities) return unserialize($cache_cities);
        $cities_list = Cities::pluck('name', 'id')->toArray();
        Redis::set('valuta:cities', serialize($cities_list));


        return $cities_list;

    }

    private function getMapDate($latlong, $city_id = 0)
    {

        $cache_exchangers = Redis::get('valuta:exchangersMap:' . $latlong);
//        if ($cache_exchangers) return unserialize($cache_exchangers);
        $exchangers = DB::table('exchangers')
            ->where('is_visible', '=', '1')
            ->whereNotNull('coordinates');

        if ($city_id > 0) {

            $exchangers->where('city', '=', $city_id);
        } else {
            $exchangers->whereRaw("CONCAT(SUBSTRING_INDEX(coordinates,'.',1),CAST(SUBSTRING_INDEX(coordinates,',',-1) AS UNSIGNED)) = $latlong");

        }
        $cache_exchangers = Redis::set('valuta:exchangersMap:' . $latlong, serialize($exchangers->get()));

        return $exchangers->get();
    }

    private function getCoordUser()
    {

//        $request = file_get_contents("http://api.sypexgeo.net/json/" . $_SERVER['REMOTE_ADDR']);
//        $array = json_decode($request);
//        return $array->city->name_ru;


//
        $client = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote = @$_SERVER['REMOTE_ADDR'];
        $result = array('country' => '', 'city' => '');

        if (filter_var($client, FILTER_VALIDATE_IP)) $ip = $client;
        elseif (filter_var($forward, FILTER_VALIDATE_IP)) $ip = $forward;
        else $ip = $remote;
        //$ip = '212.154.252.19';

        $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
        if ($ip_data && $ip_data->geoplugin_countryName != null) {
            $result = $ip_data;
        }
//        dd((int)$result->geoplugin_latitude.((int)$result->geoplugin_longitude));
        return (int)$result->geoplugin_latitude . ((int)$result->geoplugin_longitude);
//
//        $ip = $_SERVER['REMOTE_ADDR'];
//        $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}"));
//        dd($details);
//        $result = explode(',', $details->loc);
//        return (int)$result[0].(int)$result[1];
    }

    private function Cash($city = null, $parent = 0)
    {
        $latlong = $this->getCoordUser();
        $cache_cash = Redis::get('valuta:cash:' . $city);
//           if ($cache_cash) return unserialize($cache_cash);
        $cash = DB::table('front_page_data')
            ->join('exchangers', 'exchangers.id', '=', 'front_page_data.exchange_id')
            ->select('front_page_data.*',
                'exchangers.address',
                'exchangers.email',
                'exchangers.is_open',
                'exchangers.location',
                'exchangers.parent',
                'exchangers.phones',
                'exchangers.picture',
                'exchangers.coordinates',
                'exchangers.title',
                'exchangers.parent',
                'exchangers.id as exchangerID',
                'exchangers.service_exchanger',
                'exchangers.group_name as groupName',
                'exchangers.diff_exchange as diffExchange',
                DB::raw('(Select count(*) from exchangers as ex where  ex.parent = exchangers.id ) as CountChild')
            )
            ->where('exchangers.is_visible', '=', 1);
        if ($city != null and $city != 'home') {
            $cash->where('exchangers.city', '=', $city);

        } else {
            $cash->whereRaw("CONCAT(SUBSTRING_INDEX(coordinates,'.',1),CAST(SUBSTRING_INDEX(coordinates,',',-1) AS UNSIGNED)) = $latlong");

        }

        if ($parent > 0)
            $cash->where(function ($query) use ($parent) {
                $query->where('exchangers.parent', $parent)
                    ->orWhere('exchangers.id', $parent);
            });
        else
            $cash->where('exchangers.parent', 0);


        $cash = $cash->orderBy('front_page_data.update_list_at', 'desc')
            ->orderBy('exchangers.order_number', 'asc')
            ->get()
            ->map(function ($model) {
                $model->rates_json = json_decode($model->rates_json, true);
                return $model;
            });
        Redis::set('valuta:cash:' . $city, serialize($cash));

        return $cash;
    }

    public function index()
    {
        $parent = request('parent', 0);
        $parent_has = false;
        if ($parent > 0) {
            $parent_has = true;
        }

        $currency = $this->Currency();
        $cities_list = $this->City();
        $cities = Cities::pluck('name', 'id')->toArray();

        $cash = $this->Cash('home', $parent);
//        dd($cash);
        if ($cash->isNotEmpty()) {
            $ids = $cash->map(function ($model) {
                return $model->exchange_id;
            })->filter()->implode(',');
            $oldRates = $this->OldRates($ids, 'home');
        } else {
            $oldRates = [];
        }
        $latlong = $this->getCoordUser();

        $exchangers = $this->getMapDate($latlong) != null ? $this->getMapDate($latlong) : [];
        $infos = [];
        $points = [];
        foreach ($exchangers as $exchanger) {


            if ($exchanger->coordinates) {
                if ($exchanger->picture) {
                    $img = "/images/exchange/$exchanger->id/$exchanger->picture";
                } else {
                    $img = "/img/no_photo.png";
                }
                $infos[] = [

                    'balloonContentBody' => "
                    <div class='ballon-point'>
                    <img src='$img' class='img-responsive' alt=''>
                    <br>
                    $exchanger->address
                    <br>
                    $exchanger->phones
                     </div>",

                ];
                $points[] =
                    explode(",", $exchanger->coordinates);
            }
        }

        $currency_list = $currency ? $currency->map(function ($model){
            return $model->code;
        })->toArray() : [];

        $dataCalc = [];
        if($cash){
            $dataCalc = $cash->map(function ($model,$key){
                return [
                    'exchange_id'=> $model->exchangerID,
                    'rates_json'=>$model->rates_json,
                    'currencies'=>$this->getCurrencyFromRates($model->rates_json)
                ];
            });
        }

       $city = 'home';

        return view('pages.home', compact(
            'currency',
                  'cities_list',
                     'cash',
                     'oldRates',
                     'parent_has',
                      'points',
                      'infos',
                      'cities',
                      'latlong',
                       'currency_list',
                       'dataCalc',
                        'parent',
                        'city'
        ));
    }

    public function exchangeMap()
    {

        $latlong = $this->getCoordUser();

        $exchangers = $this->getMapDate($latlong) != null ? $this->getMapDate($latlong) : [];
        $infos = [];
        $points = [];
        foreach ($exchangers as $exchanger) {


            if ($exchanger->coordinates) {
                if ($exchanger->picture) {
                    $img = "/images/exchange/$exchanger->id/$exchanger->picture";
                } else {
                    $img = "/img/no_photo.png";
                }
                $infos[] = [

                    'balloonContentBody' => "
                    <div class='ballon-point'>
                    <img src='$img' class='img-responsive' alt=''>
                    <br>
                    $exchanger->address
                      <br>
                    $exchanger->phones
                     </div>",

                ];
                $points[] =
                    explode(",", $exchanger->coordinates);
            }
        }


        return view('pages.exchange-map', compact('points', 'infos'));
    }

    public function getNewCash()
    {

        $currency = $this->Currency();
        $parent = \request('parent',0);
        $city = \request('city','home');
        $parent_has = false;
        if ($parent > 0) {
            $parent_has = true;
        }
        $cash = $this->Cash($city,$parent);
        if ($cash->isNotEmpty()) {
            $ids = $cash->map(function ($model) {
                return $model->exchange_id;
            })->filter()->implode(',');
            $oldRates = $this->OldRates($ids, $city);
        } else {
            $oldRates = [];
        }

        $view = \Illuminate\Support\Facades\View::make('partials.rates_table_ajax')
                        ->with('currency',$currency)
                        ->with('cash',$cash)
                        ->with('oldRates',$oldRates)
                        ->with('parent_has',$parent_has);
        $view = $view->renderSections();
        return response()->json($view);
    }

    public function ratesjson()
    {
        if (!Auth::guest() && Auth::user()->admin == 1) {
            Redis::flushDB();
            $front = Cash::all()->map(function ($model) {
                $currencies = [
                    'usd', 'eur', 'rur',
                    'kgs', 'cny', 'gbp',
                    'chf', 'uzs', 'jpy'
                ];
                $data = [];
                foreach ($currencies as $currency) {
                    $buy = $currency . '_buy';
                    $sell = $currency . '_sell';
                    $data[$buy] = $model->{$buy};
                    $data[$sell] = $model->{$sell};
                }


                return [
                    'id' => $model->id,
                    'json_data' => $data
                ];
            })->all();

            //dd($front);

            foreach ($front as $item) {
                DB::table('front_page_data')->where('id', $item['id'])->update([
                    'rates_json' => json_encode($item['json_data'])
                ]);
            }
        }
        return redirect('/home')->with('message', 'Success!');
    }

    public function OldRates($ids, $city)
    {
        $redis_key = 'old_rates:cash:' . $city;
        $cache_cash = Redis::get($redis_key);
        if ($cache_cash) return unserialize($cache_cash);
        $cache_cash = SqlHelper::getRatValues($ids);
        Redis::set($redis_key, serialize($cache_cash));
        return $cache_cash;
    }


    public function updateCustomerRecord(Request $request)
    {
        $data = $request->all(); // This will get all the request data.

        dd($data); // This will dump and die
    }

    public function exchangeTable($id)
    {

        $latlong = $this->getCoordUser();

        $exchangers = $this->getMapDate($latlong, $id) != null ? $this->getMapDate($latlong, $id) : [];
        $parent = request('parent', 0);
        $parent_has = false;
        if ($parent > 0) {
            $parent_has = true;
        }

        $currency = $this->Currency();
        $cities_list = $this->City();
        $cities = Cities::pluck('name', 'id')->toArray();

        $cash = $this->Cash($id, $parent);
        if ($cash->isNotEmpty()) {
            $ids = $cash->map(function ($model) {
                return $model->exchange_id;
            })->filter()->implode(',');
            $oldRates = $this->OldRates($ids, $id);
        } else {
            $oldRates = [];
        }
        $infos = [];
        $points = [];
        foreach ($exchangers as $exchanger) {


            if ($exchanger->coordinates) {
                if ($exchanger->picture) {
                    $img = "/images/exchange/$exchanger->id/$exchanger->picture";
                } else {
                    $img = "/img/no_photo.png";
                }
                $infos[] = [

                    'balloonContentBody' => "
                    <div class='ballon-point'>
                    <img src='$img' class='img-responsive' alt=''>
                    <br>
                    $exchanger->address
                    <br>
                    $exchanger->phones
                     </div>",

                ];
                $points[] =
                    explode(",", $exchanger->coordinates);
            }
        }
        $currency_list = $currency ? $currency->map(function ($model){
            return $model->code;
        })->toArray() : [];
        $dataCalc = [];
        if($cash){
           $dataCalc = $cash->map(function ($model,$key){
               return [
                   'exchange_id'=> $model->exchangerID,
                   'rates_json'=>$model->rates_json,
                   'currencies'=>$this->getCurrencyFromRates($model->rates_json)
               ];
           });
        }
        $city = $id;
        //dd($dataCalc);
        return view('pages.home',
            compact('currency',
                'cities_list',
                'cash',
                'oldRates',
                'parent_has',
                'points',
                'infos',
                'cities',
                'id',
                'latlong',
                'currency_list',
                'dataCalc',
                'parent',
                'city'
            )
        );
    }

    public function exchangerSet()
    {
        if (!Auth::guest() && Auth::user()->admin == 1) {
            $models = DB::table('front_page_data')->get()->map(function ($model) {
                $model->rates_json = json_decode($model->rates_json, true);
                return $model;
            })->all();

            foreach ($models as $model) {
                $list = array_keys($model->rates_json);
                $data = [];
                foreach ($list as $item) {
                    $l = str_replace('_buy', '', $item);
                    $l = strtoupper(str_replace('_sell', '', $l));
                    $data[] = $l;
                }
                $data = implode(',', array_unique($data));
                $currencyDTO = new ExchangeCurrencyDTO($model->exchange_id, $data);
                ExchangeCurrency::create($currencyDTO);

            }
        }
        return redirect('/home')->with('message', 'Success!');

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