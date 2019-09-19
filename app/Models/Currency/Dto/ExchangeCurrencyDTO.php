<?php


namespace App\Models\Currency\Dto;


use App\Currency;

class ExchangeCurrencyDTO
{
    public $exchange_id;
    public $list;


    public function __construct($exchange_id,$list)
    {
        $this->exchange_id = $exchange_id;
        $this->list = $list;
    }


    protected function getCurrencies()
    {
        return explode(',',$this->list);
    }

    public function getCurrenciesIds()
    {
        $models = Currency::select('currency_id')
                    ->whereIn('code',$this->getCurrencies())
                    ->get()
                    ->toArray();
        return $models;
    }

    public function getInsertData()
    {
        $data = [];
        $currencyIds = $this->getCurrenciesIds();
        foreach ($currencyIds as $currencyId):
            $data[]=[
                'currency_id'=>$currencyId['currency_id'],
                'exchange_id'=>$this->exchange_id
            ];
        endforeach;
       return $data;
    }

}