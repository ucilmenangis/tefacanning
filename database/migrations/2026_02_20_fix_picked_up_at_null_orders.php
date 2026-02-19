<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Fix orders that have status='picked_up' but picked_up_at is NULL.
     * This was caused by changing status via the edit form instead of
     * using the pickup action button on the table.
     */
    public function up(): void
    {
        // Set picked_up_at to updated_at for broken orders (best available timestamp)
        DB::table('orders')
            ->where('status', 'picked_up')
            ->whereNull('picked_up_at')
            ->update(['picked_up_at' => DB::raw('updated_at')]);
    }

    public function down(): void
    {
        // No rollback needed — we can't distinguish which were originally null
    }
};
