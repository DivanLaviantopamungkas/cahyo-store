<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trancsaction;
use Illuminate\Http\Request;

class TransactionController extends BaseAdminController
{

    public function index(Request $request)
    {
        $status = $request->string('status')->toString();
        $search = $request->string('q')->toString();

        $transactions = Trancsaction::query() // Ubah ini
            ->with('user')
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($search, fn($q) => $q->where('invoice', 'like', "%{$search}%"))
            ->latest()
            ->paginate(25);

        return $this->view('transactions.index', compact('transactions', 'status', 'search'));
    }


    public function show(Trancsaction $transaction)
    {
        $transaction->load('user', 'items.product', 'items.nominal', 'items.voucherCode');
        return $this->view('transactions.show', compact('transaction'));
    }

    public function markProcessing(Trancsaction $transaction)
    {
        $transaction->update(['status' => 'processing']);

        return redirect()
            ->route('admin.transactions.show', $transaction)
            ->with('toast', ['type' => 'success', 'message' => 'Transaksi di-set processing.']);
    }

    public function markCompleted(Trancsaction $transaction)
    {
        $transaction->update(['status' => 'completed']);

        return redirect()
            ->route('admin.transactions.show', $transaction)
            ->with('toast', ['type' => 'success', 'message' => 'Transaksi di-set completed.']);
    }
}
