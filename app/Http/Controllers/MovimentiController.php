<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovimentoAcquistoRequest;
use App\Http\Requests\MovimentoVenditaRequest;
use App\Models\PortafoglioAzioni;
use App\Models\TContoTrading;
use App\Models\TMovimenti;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class MovimentiController extends Controller
{
    function vendi(MovimentoVenditaRequest $request)
    {
        // Validate request data if not done already by MovimentoVenditaRequest

        $criptoValutaID = (string) $request->input('CriptoValutaID');
        $contoTradingID = $request->input('ContoTradingID');
        $qty = $request->input('qty');

        // Check if user has enough quantity to sell
        $cryptoInMyWallet = PortafoglioAzioni::where('ContoTradingID', $contoTradingID)
            ->where('CriptoValutaID', $criptoValutaID)
            ->first();

        if (!$cryptoInMyWallet || $cryptoInMyWallet->Quantita < $qty) {
            return response()->json('Cannot sell ' . $criptoValutaID . ' for contoTradingID ' . $contoTradingID . ': Insufficient quantity');
        }

        // Fetch current market price
        $response = Http::get('https://api.kucoin.com/api/v1/market/stats?symbol=' . $criptoValutaID);
        $last = $response['data']['last'];

        // Record the transaction in TMovimenti
        $new_movimento = new TMovimenti([
            'DataMovimento' => Carbon::now(),
            'ContoTradingID' => $contoTradingID,
            'TipoMovimento' => 1, // Assuming 1 represents a sell transaction
            'CriptoValutaID' => $criptoValutaID,
            'Quantita' => $qty, // Negative quantity for sell
            'PrezzoUnitario' => $last
        ]);
        $new_movimento->save();

        // Update user's account balance (Saldo)
        $valueToAdd = $qty * $last;
        $contoTrading = TContoTrading::where('ContoTradingID', $contoTradingID)->first();
        $contoTrading->Saldo += $valueToAdd;
        $contoTrading->save();

        // Update PortafoglioAzioni: reduce quantity or create new record if not exists
        if ($cryptoInMyWallet) {
            $cryptoInMyWallet->Quantita -= $qty;
            $cryptoInMyWallet->save();
        } else {
            $new_azione = new PortafoglioAzioni([
                'ContoTradingID' => $contoTradingID,
                'CriptoValutaID' => $criptoValutaID,
                'Quantita' => $qty
            ]);
            $new_azione->save();
        }

        // Return success response or appropriate message
        return response()->json('Successfully sold ' . $qty . ' ' . $criptoValutaID . ' for ' . $valueToAdd);
    }


    function acquista(MovimentoAcquistoRequest $request)
    {

        $criptoValutaID = (string) $request->input('CriptoValutaID');
        $contoTradingID = $request->input('ContoTradingID');
        $qty = $request->input('qty');

        // Fetch current market price
        $response = Http::get('https://api.kucoin.com/api/v1/market/stats?symbol=' . $criptoValutaID);
        $last = $response['data']['last'];

        // Calculate total cost of purchase
        $totalCost = $qty * $last;

        // Check if user has enough balance to make the purchase
        $contoTrading = TContoTrading::where('ContoTradingID', $contoTradingID)->first();

        if (!$contoTrading || $contoTrading->Saldo < $totalCost) {
            return response()->json('Cannot buy ' . $qty . ' ' . $criptoValutaID . ': Insufficient balance');
        }

        // Record the transaction in TMovimenti
        $new_movimento = new TMovimenti([
            'DataMovimento' => Carbon::now(),
            'ContoTradingID' => $contoTradingID,
            'TipoMovimento' => 2, // Assuming 2 represents a buy transaction
            'CriptoValutaID' => $criptoValutaID,
            'Quantita' => $qty,
            'PrezzoUnitario' => $last
        ]);
        $new_movimento->save();

        // Update user's account balance (Saldo)
        $contoTrading->Saldo -= $totalCost;
        $contoTrading->save();

        // Update PortafoglioAzioni: increase quantity or create new record if not exists
        $cryptoInMyWallet = PortafoglioAzioni::where('ContoTradingID', $contoTradingID)
            ->where('CriptoValutaID', $criptoValutaID)
            ->first();

        if ($cryptoInMyWallet) {
            $cryptoInMyWallet->Quantita += $qty;
            $cryptoInMyWallet->save();
        } else {
            $new_azione = new PortafoglioAzioni([
                'ContoTradingID' => $contoTradingID,
                'CriptoValutaID' => $criptoValutaID,
                'Quantita' => $qty
            ]);
            $new_azione->save();
        }

        // Return success response or appropriate message
        return response()->json('Successfully bought ' . $qty . ' ' . $criptoValutaID . ' for ' . $totalCost);
    }
}
