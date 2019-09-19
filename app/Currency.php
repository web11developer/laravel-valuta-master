<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model {

    protected $table = 'cash_currencies';

    public $timestamps = false;
    protected $primaryKey = 'currency_id';
    protected $fillable =[
        'name',
        'code',
        'order_number',
        'visible_bool'
    ];

    public  function getRouteKeyName()
    {
        return 'currency_id';
    }

}