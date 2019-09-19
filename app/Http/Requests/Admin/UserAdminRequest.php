<?php

namespace App\Http\Requests\Admin;

use App\User;
use Illuminate\Foundation\Http\FormRequest;

class UserAdminRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->segment(3) != "") {
            $user = User::find($this->segment(3));
        }

        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST': {
                return [
                    'username' => 'required|min:3|unique:users,username',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required',
                ];
            }
            case 'PUT':
            case 'PATCH': {
                return [
                    'username' => 'required|min:3|unique:users,username,' . $user->id,
                    'email' => 'required|email|unique:users,email,' . $user->id,
                ];
            }
            default:
                break;
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

}
