<?php

namespace App\Http\Controllers;

use App\Http\Requests\getPortafoglioValueOfConto;
use App\Models\PortafoglioAzioni;
use Illuminate\Support\Facades\Http;

class PortafoglioAzioniController extends Controller
{

    function getPortafoglioValueOfConto(getPortafoglioValueOfConto $request)
    {
        $my_azioni = PortafoglioAzioni::where('ContoTradingID', $request['ContoTradingID'])->get();
        $totalValue = 0;

        foreach ($my_azioni as $azione) {
            $response = Http::get('https://api.kucoin.com/api/v1/market/stats', [
                'symbol' => $azione->CriptoValutaID
            ]);

            if ($response->successful()) {
                $lastPrice = $response['data']['last'];
                $totalValue += $lastPrice * $azione->Quantita;
            }
        }

        return $totalValue;
    }

}
