<?php


namespace App\Helpers;


use Illuminate\Support\Facades\DB;

class SqlHelper
{
    /**
     * @param $ids
     * @return array
     */
    public static function getRatValues($ids)
    {
        $sql = "SELECT   t.exchange_id as exchangeId,t.currency_id as currencyId,
                substring_index(group_concat(t.rate_value_buy  ORDER BY t.rate_id DESC SEPARATOR ','), ',', 2) as rate_value_buy,
                substring_index(group_concat(t.rate_value_buy  ORDER BY t.rate_id DESC SEPARATOR ','), ',', 2) as rate_value_sell 
        FROM cash_rates as t
        WHERE t.exchange_id IN (".$ids.")
        GROUP BY exchangeId,currencyId";

      return DB::select(DB::raw($sql));

    }
}