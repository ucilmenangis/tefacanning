<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    protected string $token;
    protected string $device;
    protected string $apiUrl = 'https://api.fonnte.com/send';

    public function __construct()
    {
        $this->token = config('services.fonnte.token', '');
        $this->device = config('services.fonnte.device', '');
    }

    /**
     * Send WhatsApp message via Fonnte API.
     */
    public function sendMessage(string $phone, string $message): bool
    {
        if (empty($this->token)) {
            Log::warning('Fonnte: Token not configured. Skipping WhatsApp notification.');
            return false;
        }

        try {
            $payload = [
                'target' => $phone,
                'message' => $message,
            ];

            if (!empty($this->device)) {
                $payload['device'] = $this->device;
            }

            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post($this->apiUrl, $payload);

            if ($response->successful()) {
                Log::info('Fonnte: Message sent to ' . $phone);
                return true;
            }

            Log::error('Fonnte: Failed to send message', [
                'phone' => $phone,
                'response' => $response->json(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Fonnte: Exception - ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send order confirmation to customer.
     */
    public function sendOrderConfirmation($order): bool
    {
        $order->load(['customer', 'batch', 'products']);

        $items = $order->products->map(function ($product) {
            return "- {$product->name} x{$product->pivot->quantity} @ Rp " . number_format($product->pivot->unit_price, 0, ',', '.');
        })->join("\n");

        $message = "🛒 *Konfirmasi Pesanan TEFA Canning*\n\n"
            . "Halo {$order->customer->name},\n\n"
            . "Pesanan Anda telah dikonfirmasi!\n\n"
            . "📋 *No. Pesanan:* {$order->order_number}\n"
            . "📦 *Batch:* {$order->batch->name}\n"
            . "📅 *Event:* {$order->batch->event_name}\n\n"
            . "*Detail Pesanan:*\n{$items}\n\n"
            . "💰 *Total:* Rp " . number_format($order->total_amount, 0, ',', '.') . "\n\n"
            . "🔑 *Kode Pickup:* {$order->pickup_code}\n"
            . "_(Simpan kode ini untuk pengambilan barang)_\n\n"
            . "Terima kasih telah berbelanja di TEFA Canning Polije! 🙏";

        return $this->sendMessage($order->customer->phone, $message);
    }

    /**
     * Send ready for pickup notification to customer.
     */
    public function sendReadyForPickup($order): bool
    {
        $order->load(['customer', 'batch']);

        $message = "✅ *Pesanan Siap Diambil!*\n\n"
            . "Halo {$order->customer->name},\n\n"
            . "Pesanan Anda sudah siap untuk diambil!\n\n"
            . "📋 *No. Pesanan:* {$order->order_number}\n"
            . "📦 *Batch:* {$order->batch->name}\n"
            . "🔑 *Kode Pickup:* {$order->pickup_code}\n\n"
            . "📍 Silakan datang ke lokasi TEFA Canning Polije\n"
            . "⏰ Jam operasional: Senin - Jumat, 08:00 - 16:00 WIB\n\n"
            . "Jangan lupa bawa kode pickup Anda! 🙏";

        return $this->sendMessage($order->customer->phone, $message);
    }
}
