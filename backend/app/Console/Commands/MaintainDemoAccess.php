<?php

namespace App\Console\Commands;

use App\Mail\DemoExpiryReminder;
use App\Models\DemoUser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class MaintainDemoAccess extends Command
{
    protected $signature = 'demo-access:maintain';

    protected $description = 'Expire old demo users and send reminder emails for expiring accounts.';

    public function handle(): int
    {
        $expiredCount = DemoUser::query()
            ->where('status', 'Active')
            ->where('expiry_date', '<=', now())
            ->update(['status' => 'Expired']);

        $reminderUsers = DemoUser::query()
            ->with('demoRequest')
            ->where('status', 'Active')
            ->whereBetween('expiry_date', [now()->addDay()->startOfDay(), now()->addDay()->endOfDay()])
            ->get();

        foreach ($reminderUsers as $demoUser) {
            if ($demoUser->demoRequest) {
                Mail::to($demoUser->demoRequest->email)->send(new DemoExpiryReminder($demoUser->demoRequest, $demoUser));
            }
        }

        $this->info("Expired {$expiredCount} demo account(s) and sent {$reminderUsers->count()} reminder email(s).");

        return self::SUCCESS;
    }
}
