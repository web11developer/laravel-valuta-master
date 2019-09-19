<?php
/**
 * Created by PhpStorm.
 * User: Shokhaa
 * Date: 9/4/19
 * Time: 2:25 PM
 */

namespace App\Http\Controllers;



use App\Http\Requests\ExchangersRequest;
use App\Http\Requests\SubscribesRequest;
use App\Http\Requests\SubscrRequest;
use App\Http\Services\SubscribesService;
use App\Subscribes;

class SubscribesController extends Controller
{
    public function addSubscribe(SubscrRequest $request)
    {
        try {

            SubscribesService::emailExists($request->input('email'), $request->input('exchanger_id'));
            $subscribe = new Subscribes();
            $subscribe->email = $request->input('email');
            $subscribe->exchange_id = $request->input('exchanger_id');
            $subscribe->save();
            return redirect('/exchange/'.$request->input('exchanger_id'))->with('message', 'Вы успешно подписались на изменения курсов валют');

        } catch (\Exception $e) {

            return redirect('/exchange/'.$request->input('exchanger_id'))->with('message-error', $e->getMessage());


        }


    }



}