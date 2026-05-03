<?php

namespace App\Console\Commands;

use App\Models\Subscription\Subscription;
use App\Models\Subscription\SubscriptionRenewal;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Daily subscription lifecycle command (idempotent).
 *
 * 1. Marks manual subscriptions as expired when next_billing_date is in the past.
 * 2. Auto-renews subscriptions where renewal_type=automatic, status=active,
 *    and next_billing_date <= today: creates a renewal record and extends
 *    next_billing_date by one month.
 */
class ProcessSubscriptionRenewals extends Command
{
    protected $signature = 'subscriptions:process-renewals
                            {--dry-run : List changes without applying them}';

    protected $description = 'Process subscription renewals and expire past-due manual subscriptions';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        if ($dryRun) {
            $this->warn('Dry run — no changes will be made.');
        }

        // Step 1: Expire manual subscriptions that are past due
        $toExpire = Subscription::manualPastDue()->get();
        $expiredCount = 0;
        foreach ($toExpire as $subscription) {
            if ($dryRun) {
                $this->line("Would expire subscription #{$subscription->id} (client #{$subscription->client_id}, next_billing_date: {$subscription->next_billing_date->format('Y-m-d')})");
            } else {
                $subscription->update(['status' => 'expired']);
                $expiredCount++;
            }
        }
        if (!$dryRun && $expiredCount > 0) {
            $this->info("Expired {$expiredCount} manual subscription(s).");
        }

        // Step 2: Auto-renew subscriptions due for renewal
        $toRenew = Subscription::dueForAutoRenewal()->get();
        $renewedCount = 0;
        foreach ($toRenew as $subscription) {
            $oldNext = $subscription->next_billing_date;
            $newNext = $oldNext->copy()->addMonth();
            $amount = $subscription->plan_price;

            if ($dryRun) {
                $this->line("Would auto-renew subscription #{$subscription->id} (client #{$subscription->client_id}): next_billing_date {$oldNext->format('Y-m-d')} → {$newNext->format('Y-m-d')}");
                $renewedCount++;
                continue;
            }

            try {
                DB::transaction(function () use ($subscription, $newNext, $amount) {
                    $subscription->update(['next_billing_date' => $newNext]);
                    SubscriptionRenewal::create([
                        'subscription_id'     => $subscription->id,
                        'renewed_at'          => now(),
                        'next_billing_date'   => $newNext,
                        'renewal_type'        => 'automatic',
                        'amount'              => $amount,
                    ]);
                });
                $renewedCount++;
            } catch (\Throwable $e) {
                $this->error("Failed to renew subscription #{$subscription->id}: " . $e->getMessage());
            }
        }

        if (!$dryRun && $renewedCount > 0) {
            $this->info("Auto-renewed {$renewedCount} subscription(s).");
        }

        $this->info('Done.');
        return Command::SUCCESS;
    }
}
