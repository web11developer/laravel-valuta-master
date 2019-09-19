<?php
/**
 * Created by PhpStorm.
 * User: Shokhaa
 * Date: 9/4/19
 * Time: 3:05 PM
 */

namespace App\Http\Services;


use App\Subscribes;
use Illuminate\Support\Facades\DB;

class SubscribesService
{
    /**
     *
     */
    public static function emailExists($email, $id)
    {
//        dd(DB::table('subscribes')->where('email', '=', $email)->where('exchange_id', '=', $id)->exists());
        if (DB::table('subscribes')->where('email', '=', $email)->where('exchange_id', '=', $id)->exists()){
            throw new \DomainException('Email is already added');

        }
        return true;
    }

}