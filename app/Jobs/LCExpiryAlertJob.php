<?php

namespace App\Jobs;

use App\Models\LCRecord;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class LCExpiryAlertJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Logic to check for expiring LCs
        $upcomingExpiry = LCRecord::whereDate('expiry_date', '<=', Carbon::now()->addDays(7))
            ->whereDate('expiry_date', '>=', Carbon::now())
            ->where('payment_status', 'PENDING')
            ->get();

        foreach ($upcomingExpiry as $lc) {
            // Trigger notification (Log for now, or Notification check if requested later)
            Log::info("LC Expiring soon: {$lc->lc_number} on {$lc->expiry_date}");
            // Notification::send(..., new LCExpiringNotification($lc));
        }
    }
}
