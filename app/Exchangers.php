<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;

class Exchangers extends Model
{
    use SoftDeletes;
    const CURRENT_OPEN = 1;
    const CURRENT_CLOSE = 2;
    const ALL_OPEN = 3;
    protected $dates = ['deleted_at'];
    protected $guarded = array('id');

    public function owner()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function cities()
    {
        return $this->belongsTo('App\Cities', 'city', 'id');
    }

    public function cash()
    {
        return $this->belongsTo('App\Cash', 'id', 'exchange_id');
    }

    public function parents()
    {
        return $this->belongsTo('App\Exchangers', 'id', 'parent');
    }

    public function followers()
    {
        return $this->hasMany('App\Subscribes', 'exchange_id');
    }

    public function picture($withBaseUrl = false)
    {
        if (!$this->picture) return NULL;

        $imgDir = '/images/' . $this->id;
        $url = $imgDir . '/' . $this->picture;

        return $withBaseUrl ? URL::asset($url) : $url;
    }

    public function Currencies()
    {
        return $this->belongsToMany('App\Currency', 'exchange_currencies', 'exchange_id', 'currency_id');
    }

}
