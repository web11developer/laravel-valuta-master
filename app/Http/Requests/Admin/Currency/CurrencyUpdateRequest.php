<?php


namespace App\Http\Requests\Admin\Currency;


use Illuminate\Foundation\Http\FormRequest;

class CurrencyUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:3',
            'code' => 'required|unique:cash_currencies,code,'.$this->route('currency').',currency_id',
            'order_number' => 'required',
            'visible_bool' => 'required',
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