<?php
/**
 * Created by PhpStorm.
 * User: Shokhaa
 * Date: 9/4/19
 * Time: 2:38 PM
 */

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class SubscrRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            'exchanger_id' => 'integer'
        ];
    }
}