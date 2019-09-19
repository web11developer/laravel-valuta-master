<?php
namespace App\Http\Requests;

use App\Rules\HasParent;
use Illuminate\Foundation\Http\FormRequest;

class ExchangersRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */


    public function rules()
    {

        return [
            'title' => 'required|min:3|unique:exchangers,title,'.$this->segment(2),
            'city' => 'required|integer',
            'email' => 'email',
            'address' => 'required|min:10',
            'is_visible' => 'integer',
            'location' => 'string|max:255',
            'order_number' => 'required|integer',
            'del_picture' => 'integer',
            'coordinates' => 'required|string',
            'is_open' => 'required|integer',
            'parent'=>[ new HasParent($this->segment(2,0),$this->request->get('parent',0))]
        ];
    }
    public function messages()
    {
// use trans instead on Lang
        return [
            'coordinates.required' => 'map empty',
            'address.required' => 'Поле Адрес обязательно для заполнения.',
            'is_open.required' => 'Обязательно надо выберать из списка.',

        ];
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