<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MovimentoVenditaRequest extends FormRequest
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
            'CriptoValutaID' => 'required|in:BTC-USDT,EOS-USDT,XRP-USDT,BCH-USDT,ADA-USDT',
            'qty' => 'required|min:1',
            'ContoTradingID' => 'required|exists:TContoTrading,ContoTradingID'
        ];
    }
}
