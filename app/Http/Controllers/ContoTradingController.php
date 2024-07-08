<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContoTradingListRequest;
use App\Http\Requests\GetMovimentiForConto_DateRange;
use App\Models\TContoTrading;
use App\Models\TMovimenti;

class ContoTradingController extends Controller
{
    public function list(ContoTradingListRequest $request)
    {
        if ($request['maxQty']) {
            $conti = TContoTrading::all()->take($request['maxQty']);
        } else {
            $conti = TContoTrading::all();
        }
        return response()->json($conti);
    }


    public function getMovimenti(GetMovimentiForConto_DateRange $request)
    {
        $contoTradingID = $request->input('ContoTradingID');
        $dataMin = $request->input('DataMin');
        $dataMax = $request->input('DataMax');
        $maxQty = $request->input('maxQty');

        // Costruzione della query per ottenere i movimenti
        $query = TMovimenti::where('ContoTradingID', $contoTradingID);

        if ($dataMin) {
            $query->where('DataMovimento', '>=', $dataMin);
        }

        if ($dataMax) {
            $query->where('DataMovimento', '<=', $dataMax);
        }

        if ($maxQty) {
            $query->limit($maxQty);
        }

        $movimenti = $query->get();

        return response()->json($movimenti);
    }


    function totaliAcquistiVendite($ContoTradingID)
    {

        $my_movimenti = TMovimenti::where('ContoTradingID', $ContoTradingID);

        $totale_acquisti = 0;
        $totale_vendite = 0;

        $acquisti = TMovimenti::where('ContoTradingID', $ContoTradingID)->where('TipoMovimento', 2)->get();
        $vendite = TMovimenti::where('ContoTradingID', $ContoTradingID)->where('TipoMovimento', 1)->get();


        foreach ($acquisti as $acq) {
            $totale_acquisti += $acq->PrezzoUnitario * $acq->Quantita;
        }
        foreach ($vendite as $ven) {
            $totale_vendite += $ven->PrezzoUnitario * $ven->Quantita;
        }

        return response()->json(['totale_acquisti' => $totale_acquisti, 'totale_vendite' => $totale_vendite]);
    }
}
