<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MovimentoAcquistoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Assuming authorization is handled elsewhere
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ContoTradingID' => 'required|exists:TContoTrading,ContoTradingID',
            'CriptoValutaID' => 'required|in:BTC-USDT,EOS-USDT,XRP-USDT,BCH-USDT,ADA-USDT',
            'qty' => 'required|numeric|min:1',
        ];
    }
}
