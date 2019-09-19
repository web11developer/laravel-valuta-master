<?php

namespace App\Http\Controllers;

use App\Cash;
use App\CashRates;
use App\Currency;
use App\Exchangers;
use App\Http\Requests\CashRatesRequest;
use App\Http\Requests\CashRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;


class CashRatesController extends Controller
{

    public function __construct()
    {
        view()->share('type', 'cashRates');
//        $this->middleware('auth');
    }

    public function getRates(CashRatesRequest $request)
    {

        if ($request->input('type') == 'SELL') {
            $rate_type = 'rate_value_sell';
        } else {
            $rate_type = 'rate_value_buy';
        }

        $cashRate = CashRates::select(
            DB::raw('UNIX_TIMESTAMP(changed_at) * 1000 as changed_at'), $rate_type
        )->where('exchange_id', $request->input('exchange_id'))
            ->where('currency_id', $request->input('currency_filter'))
            ->whereRaw(DB::raw('year(changed_at) = ' . $request->input('year_filter')))
            ->orderBy('changed_at', 'asc')
            ->get();

        $data = array();
        foreach ($cashRate as $record) {
            $data[] = [floatval($record['changed_at']), floatval($record[$rate_type])];
        }

        return response($data, 200)
            ->header('Content-Type', 'application/json');
    }

}
