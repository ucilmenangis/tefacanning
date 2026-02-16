<?php

namespace App\Filament\Customer\Widgets;

use App\Models\Batch;
use Filament\Widgets\Widget;

class LatestBatchWidget extends Widget
{
    protected static string $view = 'filament.customer.widgets.latest-batch-widget';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 1;

    public function getBatchData(): ?array
    {
        $batch = Batch::where('status', 'open')->latest()->first();

        if (!$batch) {
            return null;
        }

        return [
            'name' => $batch->name,
            'event_name' => $batch->event_name ?? 'Umum',
            'event_date' => $batch->event_date ? $batch->event_date->format('d M Y') : '-',
            'order_count' => $batch->orders()->count(),
            'status' => $batch->status,
        ];
    }
}
