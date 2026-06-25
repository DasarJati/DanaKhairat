<?php
// app/Console/Commands/UpdateExpiredSubscriptions.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SubscriptionsKariah;
use App\Models\SubscriptionsMasjid;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class UpdateExpiredSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:update-expired
                            {--dry-run : Run without making changes}
                            {--user-id= : Update specific user only}
                            {--days=0 : Update subscriptions expired X days ago (default: today)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update expired subscriptions status from active to expired';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $specificUserId = $this->option('user-id');
        $daysAgo = (int) $this->option('days');
        
        $this->info("=== UPDATE EXPIRED SUBSCRIPTIONS ===");
        $this->info("Current Date: " . Carbon::now()->format('Y-m-d H:i:s'));
        $this->info("Dry Run: " . ($isDryRun ? 'YES' : 'NO'));
        $this->info("User ID Filter: " . ($specificUserId ?? 'ALL'));
        $this->info("Expired since: " . ($daysAgo > 0 ? "{$daysAgo} days ago" : 'Today'));
        $this->newLine();

        // Calculate the cutoff date
        $cutoffDate = Carbon::now()->subDays($daysAgo)->startOfDay();
        $this->info("Cutoff Date: " . $cutoffDate->format('Y-m-d'));
        $this->newLine();

        $totalUpdated = 0;
        $totalSkipped = 0;
        $totalErrors = 0;

        // Process Kariah Subscriptions
        $kariahResult = $this->processSubscriptions(
            SubscriptionsKariah::class,
            'subscriptions_kariah',
            $cutoffDate,
            $specificUserId,
            $isDryRun
        );
        
        $totalUpdated += $kariahResult['updated'];
        $totalSkipped += $kariahResult['skipped'];
        $totalErrors += $kariahResult['errors'];

        // Process Masjid Subscriptions (AJK)
        $masjidResult = $this->processSubscriptions(
            SubscriptionsMasjid::class,
            'subscriptions_masjid',
            $cutoffDate,
            $specificUserId,
            $isDryRun
        );
        
        $totalUpdated += $masjidResult['updated'];
        $totalSkipped += $masjidResult['skipped'];
        $totalErrors += $masjidResult['errors'];

        // Summary
        $this->newLine();
        $this->info("=== SUMMARY ===");
        $this->info("Total Updated: {$totalUpdated}");
        $this->info("Total Skipped: {$totalSkipped}");
        $this->info("Total Errors: {$totalErrors}");
        
        if ($isDryRun) {
            $this->info("\n⚠️  DRY RUN - No changes were made to the database.");
        } else {
            $this->info("\n✅ All expired subscriptions have been updated.");
        }

        // Log the action
        Log::info('Subscription expiration update completed', [
            'updated' => $totalUpdated,
            'skipped' => $totalSkipped,
            'errors' => $totalErrors,
            'dry_run' => $isDryRun,
            'cutoff_date' => $cutoffDate->format('Y-m-d'),
            'user_id' => $specificUserId
        ]);

        return 0;
    }

    /**
     * Process subscriptions for a specific model
     */
    private function processSubscriptions($modelClass, $tableName, $cutoffDate, $specificUserId, $isDryRun)
    {
        $this->info("Processing {$tableName}...");
        
        // Build query
        $query = $modelClass::where('status', 'active')
            ->whereDate('end_date', '<=', $cutoffDate);
        
        if ($specificUserId) {
            $query->where('user_id', $specificUserId);
        }
        
        $expiredSubscriptions = $query->get();
        
        $this->info("Found " . $expiredSubscriptions->count() . " expired subscriptions in {$tableName}");
        
        $updated = 0;
        $skipped = 0;
        $errors = 0;
        
        if ($expiredSubscriptions->count() > 0) {
            // Show table of expired subscriptions
            $rows = [];
            foreach ($expiredSubscriptions as $sub) {
                $rows[] = [
                    $sub->id,
                    $sub->user_id ?? 'N/A',
                    $sub->user->nama ?? 'N/A',
                    $sub->end_date,
                    Carbon::parse($sub->end_date)->diffInDays(Carbon::now()) . ' days',
                    $sub->status
                ];
            }
            
            $this->table(
                ['ID', 'User ID', 'User Name', 'End Date', 'Overdue', 'Current Status'],
                $rows
            );
            
            if (!$isDryRun) {
                // Update each subscription
                foreach ($expiredSubscriptions as $subscription) {
                    try {
                        $subscription->update([
                            'status' => 'expired'
                        ]);
                        
                        $updated++;
                        $this->line("✅ Updated subscription #{$subscription->id} to 'expired'");
                        
                        // Log the update
                        Log::info("Subscription expired", [
                            'subscription_id' => $subscription->id,
                            'user_id' => $subscription->user_id,
                            'table' => $tableName,
                            'end_date' => $subscription->end_date,
                            'updated_at' => now()
                        ]);
                        
                    } catch (\Exception $e) {
                        $errors++;
                        $this->error("❌ Failed to update subscription #{$subscription->id}: " . $e->getMessage());
                        Log::error("Failed to update expired subscription", [
                            'subscription_id' => $subscription->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            } else {
                $this->info("⚠️  DRY RUN: Would update " . $expiredSubscriptions->count() . " subscriptions");
                $skipped = $expiredSubscriptions->count();
            }
        }
        
        $this->newLine();
        
        return [
            'updated' => $updated,
            'skipped' => $skipped,
            'errors' => $errors
        ];
    }
}