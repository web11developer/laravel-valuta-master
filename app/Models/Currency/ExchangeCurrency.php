<?php

namespace App\Models\Currency;

use App\Models\Currency\Dto\ExchangeCurrencyDTO;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ExchangeCurrency extends Model
{
    protected $table='exchange_currencies';


    public static function create(ExchangeCurrencyDTO  $dto)
    {

        DB::transaction(function () use ($dto) {
            DB::table('exchange_currencies')->where('exchange_id',$dto->exchange_id)->delete();
            ExchangeCurrency::insert($dto->getInsertData());
        });

    }

    public static function getTableName()
    {
        return 'exchange_currencies';
    }

}
