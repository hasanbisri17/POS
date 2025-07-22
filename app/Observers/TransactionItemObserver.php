<?php

namespace App\Observers;

use App\Models\TransactionItem;
use App\Models\StockMovement;

class TransactionItemObserver
{
    public function created(TransactionItem $transactionItem): void
    {
        // Update product stock
        $transactionItem->product->decrement('stock', $transactionItem->quantity);

        // Create stock movement record
        StockMovement::create([
            'product_id' => $transactionItem->product_id,
            'quantity' => $transactionItem->quantity,
            'type' => 'out',
            'notes' => 'Pengurangan stok dari transaksi penjualan',
            'created_by' => $transactionItem->transaction->created_by,
            'transaction_id' => $transactionItem->transaction_id,
        ]);
    }

    public function deleted(TransactionItem $transactionItem): void
    {
        // Update product stock
        $transactionItem->product->increment('stock', $transactionItem->quantity);

        // Create stock movement record
        StockMovement::create([
            'product_id' => $transactionItem->product_id,
            'quantity' => $transactionItem->quantity,
            'type' => 'in',
            'notes' => 'Pengembalian stok dari pembatalan transaksi',
            'created_by' => $transactionItem->transaction->created_by,
            'transaction_id' => $transactionItem->transaction_id,
        ]);
    }
}
