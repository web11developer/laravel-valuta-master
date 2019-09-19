<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscribes extends Model
{
    public function exchanger()
    {
        return $this->belongsTo('App\Exchangers', 'exchange_id');

//        return $this->hasOne('App\Exchangers', 'exchange_id');
    }
}
