<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class OrderPdfController extends Controller
{
    /**
     * Check if current user is authorized to access the order PDF.
     * Allows: admin/teknisi (web guard) OR the customer who owns the order.
     */
    private function authorizeAccess(Order $order): void
    {
        // Admin/teknisi logged in via web guard — always allowed
        if (auth()->check()) {
            return;
        }

        // Customer logged in via customer guard — must own the order
        $customer = auth('customer')->user();
        if ($customer && (int) $order->customer_id === (int) $customer->id) {
            return;
        }

        // Neither guard authenticated, or customer doesn't own this order
        if ($customer) {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
        }

        abort(401, 'Silakan login terlebih dahulu.');
    }

    public function download(Order $order)
    {
        $this->authorizeAccess($order);

        $order->load(['customer', 'batch', 'products']);

        $pdf = Pdf::loadView('pdf.order-report', compact('order'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("Pesanan-{$order->order_number}.pdf");
    }

    public function stream(Order $order)
    {
        $this->authorizeAccess($order);

        $order->load(['customer', 'batch', 'products']);

        $pdf = Pdf::loadView('pdf.order-report', compact('order'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream("Pesanan-{$order->order_number}.pdf");
    }
}
