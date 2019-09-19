<?php


namespace App\DTO;


use App\Models\Currency\Helpers\ExchangeHelper;

class CashDTO
{
    public $date;
    public $newCacheValue;
    public $request;
    public $currencies;
    public $cach;

    public function __construct($date,$newCacheValue,$request,$currencies,$cach)
    {
        $this->date = $date;
        $this->newCacheValue = $newCacheValue;
        $this->request = $request;
        $this->currencies = $currencies;
        $this->cach = $cach;

    }

    public function getItems()
    {
        return ExchangeHelper::getClearItems($this->request->get('items',[]));
    }

    public function getCashId()
    {
        return $this->cach->id;
    }
}