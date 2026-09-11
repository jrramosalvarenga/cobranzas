<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class RecibosController extends Controller
{
    public function show(string $receiptNumber)
    {
        $pagos = Payment::query()
            ->where('receipt_number', $receiptNumber)
            ->with(['cuota.contract.client', 'cuota.contract.serviceType', 'user'])
            ->get();

        abort_if($pagos->isEmpty(), 404);

        $primero = $pagos->first();

        return view('recibos.print', [
            'receiptNumber' => $receiptNumber,
            'pagos' => $pagos,
            'cliente' => $primero->cuota->contract->client,
            'total' => $pagos->sum('amount'),
            'fecha' => $primero->payment_date,
            'metodo' => $primero->method,
            'cajero' => $primero->user,
        ]);
    }
}
