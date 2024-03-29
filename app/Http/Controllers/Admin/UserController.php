<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Http\Requests\Admin\UserAdminRequest;
use App\User;
use App\Http\Requests\Admin\UserRequest;
use Datatables;
use Illuminate\Support\Facades\URL;


class UserController extends AdminController
{


    public function __construct()
    {
        view()->share('type', 'user');
    }

    /*
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {
        // Show the page
        return view('admin.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.user.create_edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserAdminRequest $request
     * @return Response
     */
    public function store(UserAdminRequest $request)
    {

        $user = new User ($request->except('password','password_confirmation'));
        $user->password = bcrypt($request->password);
        $user->confirmation_code = str_random(32);
        $user->save();
        return redirect('/admin/user');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $user
     * @return Response
     */
    public function edit(User $user)
    {
        return view('admin.user.create_edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserAdminRequest $request
     * @param User $user
     * @return Response
     */
    public function update(UserAdminRequest $request, User $user)
    {
        $password = $request->password;
        $passwordConfirmation = $request->password_confirmation;

        if (!empty($password)) {
            if ($password === $passwordConfirmation) {
                $user->password = bcrypt($password);
            }
        }
        $user->update($request->except('password','password_confirmation'));
        return redirect('/admin/user');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $user
     * @return Response
     */

    public function delete(User $user)
    {
        return view('admin.user.delete', compact('user'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $user
     * @return Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect('/admin/user');
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
        $users = User::select(array('users.id', 'users.username', 'users.email',
                                'users.confirmed', 'users.created_at'));

        return Datatables::of($users)
            ->editColumn('confirmed',function ($row){
                if($row->confirmed == 1)
                    return '<span class="glyphicon glyphicon-ok"></span>';
                return '<span class="glyphicon glyphicon-remove"></span>';
            })
            ->addColumn('actions',function ($row){
                 if($row->id != 1) {
                     $url = URL::to('admin/user/' . $row->id . '/edit');
                     return '<a href="' . $url . '" class="btn btn-success btn-sm iframe" >
                                <span class="glyphicon glyphicon-pencil"></span>  
                                ' . trans("admin/modal.edit") . '</a>';
                 }
                $url = URL::to('admin/user/' . $row->id . '/delete');
                 return '<a href="'.$url.'" class="btn btn-sm btn-danger iframe">
                        <span class="glyphicon glyphicon-trash"></span> '. trans("admin/modal.delete") .'</a>';
            })
            ->rawColumns(['actions','confirmed'])
            ->make();
    }

}
