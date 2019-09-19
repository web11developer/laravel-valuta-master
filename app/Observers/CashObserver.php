<?php
/**
 * Created by PhpStorm.
 * User: Shokhaa
 * Date: 9/4/19
 * Time: 5:41 PM
 */

namespace App\Observers;


use App\Cash;

class CashObserver
{
    public function created(Cash $cash)
    {
        //вв
        dd(1);
    }

}