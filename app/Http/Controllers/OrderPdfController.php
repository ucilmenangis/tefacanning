<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class OrderPdfController extends Controller
{
    public function download(Order $order)
    {
        // Authorize: only the customer who owns the order, or admin/teknisi can download
        $user = auth()->user();
        $customer = auth('customer')->user();

        if ($customer) {
            // Customer guard: must own the order
            if ($order->customer_id !== $customer->id) {
                abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
            }
        } elseif (!$user) {
            abort(401);
        }

        $order->load(['customer', 'batch', 'products']);

        $pdf = Pdf::loadView('pdf.order-report', compact('order'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("Pesanan-{$order->order_number}.pdf");
    }

    public function stream(Order $order)
    {
        $user = auth()->user();
        $customer = auth('customer')->user();

        if ($customer) {
            if ($order->customer_id !== $customer->id) {
                abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
            }
        } elseif (!$user) {
            abort(401);
        }

        $order->load(['customer', 'batch', 'products']);

        $pdf = Pdf::loadView('pdf.order-report', compact('order'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream("Pesanan-{$order->order_number}.pdf");
    }
}
