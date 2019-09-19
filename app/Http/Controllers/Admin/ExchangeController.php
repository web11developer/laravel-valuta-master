<?php

namespace App\Http\Controllers\Admin;

use App\Exchangers;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\DataTables;

class ExchangeController extends AdminController
{

    public function __construct()
    {
        view()->share('type', 'exchange');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // Show the page
        $title = 'Обменные пункты';
        return view('admin.exchange.index', compact('title'));
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
/*
        $posts = DB::table('posts')->join('users', 'posts.user_id', '=', 'users.id')
            ->select(['posts.id', 'posts.title', 'users.name', 'users.email', 'posts.created_at', 'posts.updated_at']);

        return Datatables::of($posts)
            ->editColumn('title', '{!! str_limit($title, 60) !!}')
            ->editColumn('name', function ($model) {
                return \HTML::mailto($model->email, $model->name);
            })
            ->make(true);

        */
        $exchange = DB::table('exchangers')
            ->join('users', 'exchangers.user_id', '=', 'users.id')
            ->join('cities', 'exchangers.city', '=', 'cities.id')
            ->select([
                'exchangers.id',
                'exchangers.user_id',
                'exchangers.address',
                'exchangers.phones',
                'exchangers.title',
                'exchangers.is_visible',
                'cities.name',
                'users.username',
                'exchangers.created_at',
                'exchangers.updated_at'
            ]);


        return Datatables::of($exchange)
            ->editColumn('is_visible', '{!! ($is_visible ? "Активен" : "Деактивирован" ) !!}')
            ->editColumn('title', function ($model) {
                return '<a href="/exchange/'.$model->id. '/edit">' . $model->title .'</a> <br> ' . $model->address . '<br>' . $model->phones;
            })
            ->editColumn('username', function ($model) {
                return '<a href="/admin/user/'.$model->user_id. '/edit/">' . $model->username .'</a>';
            })->rawColumns(['title','username'])
//            ->editColumn('title', '{!! str_limit($title, 10) !!}')
            ->make(true);
    }

}
