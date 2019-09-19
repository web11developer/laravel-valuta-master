<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Language;
use App\Http\Requests\Admin\LanguageRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use yajra\Datatables\Datatables;

class LanguageController extends AdminController
{

    public function __construct()
    {
        view()->share('type', 'language');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // Show the page
        return view('admin.language.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // Show the page
        return view('admin.language.create_edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param LanguageRequest $request
     * @return Response
     */
    public function store(LanguageRequest $request)
    {
        $language = new Language($request->all());
        $language->user_id = Auth::id();
        $language->save();
        return redirect('admin/language');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Language $language
     * @return Response
     * @internal param int $id
     */
    public function edit(Language $language)
    {
        return view('admin.language.create_edit', compact('language'));
    }


    public function update(Language $language, LanguageRequest $request)
    {
        $language->user_id_edited = Auth::id();
        $language->update($request->all());
        return redirect('admin/language');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Language $language
     * @return Response
     * @internal param $id
     */

    public function delete(Language $language)
    {
        // Show the page
        return view('admin/language/delete', compact('language'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Language $language
     * @return Response
     * @throws \Exception
     * @internal param $id
     */
    public function destroy(Language $language)
    {
        $language->delete();
        return redirect('admin/language');
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
        $language = Language::whereNull('languages.deleted_at')
            ->orderBy('languages.position', 'ASC')
            ->select(array('languages.id', 'languages.name', 'languages.lang_code as lang_code'));
        return Datatables::of($language)
//            ->edit_column('icon', '<img src="blank.gif" class="flag flag-{{$icon}}" alt="" />')

            ->addColumn('actions', '<a href="{{{ URL::to(\'admin/language/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span> {{ trans("admin/modal.edit") }}</a>
                    <a href="{{{ URL::to(\'admin/language/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ trans("admin/modal.delete") }}</a>
                    <input type="hidden" name="row" value="{{$id}}" id="row">')
            ->rawColumns(['actions'])
            ->make();
    }

}
