<?php

namespace App\Observers;

use App\Models\Order;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        $yearMonth = now()->format('Ym'); // 202508
        $prefix = 'ORD-' . $yearMonth;

        // Contar cuántas órdenes ya existen este mes
        $monthlyCount = Order::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        $nextNumber = str_pad($monthlyCount + 1, 5, '0', STR_PAD_LEFT); // 0001, 0002...
        $order->order_number = "{$prefix}-{$nextNumber}";
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
