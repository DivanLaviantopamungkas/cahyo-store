<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Trancsaction;
use Illuminate\Http\Request;

class TokopayController extends Controller
{
    public function show(Trancsaction $transaction)
    {
        // Nanti: tampilkan QR / payment_url dalam view.
        return response()->json([
            'invoice' => $transaction->invoice,
            'status' => $transaction->status,
            'payment_url' => $transaction->payment_url,
        ]);
    }
}
