<?php

namespace App\Http\Controllers\Admin;


use App\Article;
use App\ArticleCategory;
use App\Currency;
use App\Http\Controllers\AdminController;
use App\Http\Requests\Admin\Currency\CurrencyCreateRequest;
use App\Http\Requests\Admin\Currency\CurrencyUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Yajra\DataTables\DataTables;

class CurrencyController extends AdminController
{

    public function __construct()
    {
        view()->share('type', 'currency');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return  view('admin.currency.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.currency.create_edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CurrencyCreateRequest $request)
    {
        $model = new Currency($request->all());
        $model -> save();
        return redirect('admin/currency');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $currency = Currency::findOrFail($id);
        return  view('admin.currency.create_edit',[
            'currency'=>$currency
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CurrencyUpdateRequest $request, $id)
    {
        $model = Currency::findOrFail($id);
        $model->update($request->all());
        return redirect('admin/currency');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$currency)
    {
        $model = Currency::findOrFail($currency);
        $model->delete();
        return redirect('admin/currency');
        //
    }


    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
        $models = Currency::select(array('currency_id','code','name','order_number','visible_bool'));

        return DataTables::of($models)
            ->addColumn('actions',function ($row){

                $form ='<form action="'.URL::to('admin/currency/'.$row->currency_id.'/destroy') .'" method="POST">
                    <a class="btn btn-success btn-sm" href="'.URL::to('admin/currency/'.$row->currency_id.'/edit').'">
                            <span class="glyphicon glyphicon-pencil"></span>
                            '. trans("admin/modal.edit") .'
                       </a>
                      <input name="_token" type="hidden" value="'.csrf_token().'">
                 
                    <button type="submit" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash"></span></button>
                </form>';

                return $form;
            })->rawColumns(['actions'])

            ->make();
    }
}
