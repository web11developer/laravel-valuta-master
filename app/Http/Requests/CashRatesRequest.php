<?php
namespace App\Http\Requests;

use App\Exchangers;
use Illuminate\Foundation\Http\FormRequest;

class CashRatesRequest extends FormRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

//        $exchange = Exchangers::find($this->segment(3));

        return [
            'type' => 'required|min:3|max:4',
            'year_filter' => 'required|integer',
            'currency_filter' => 'required|integer',
            'exchange_id' => 'required|integer',
        ];
    }

    public function authorize()
    {
        return true;
    }

}