<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Models\CashCategory;
use App\Models\CashTransaction;
use Carbon\Carbon;

class TransactionObserver
{
    public function created(Transaction $transaction): void
    {
        if ($transaction->status === 'completed') {
            $this->createCashTransaction($transaction);
        }
    }

    public function updated(Transaction $transaction): void
    {
        if ($transaction->status === 'completed' && $transaction->getOriginal('status') !== 'completed') {
            $this->createCashTransaction($transaction);
        }

        if ($transaction->status === 'cancelled' && $transaction->getOriginal('status') !== 'cancelled') {
            $transaction->cashTransaction?->delete();
        }
    }

    protected function createCashTransaction(Transaction $transaction): void
    {
        $salesCategory = CashCategory::where('name', 'Penjualan')
            ->where('is_system', true)
            ->first();

        if ($salesCategory) {
            CashTransaction::create([
                'cash_category_id' => $salesCategory->id,
                'amount' => $transaction->total_amount,
                'type' => 'income',
                'description' => "Pemasukan dari transaksi #{$transaction->invoice_number}",
                'transaction_date' => Carbon::parse($transaction->created_at)->toDateString(),
                'created_by' => $transaction->created_by,
                'transaction_id' => $transaction->id,
            ]);
        }
    }
}
