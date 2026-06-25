<?php
// app/Console/Commands/CheckExpiringSubscriptions.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\SubscriptionsKariah;
use App\Models\SubscriptionsMasjid;
use App\Mail\SubscriptionExpiringMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CheckExpiringSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:check-expiring
                            {--days=30 : Number of days before expiration to send notification}
                            {--dry-run : Run without sending emails}
                            {--user-id= : Check specific user only}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for expiring subscriptions and send email notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $daysThreshold = (int) $this->option('days');
        $isDryRun = $this->option('dry-run');
        $specificUserId = $this->option('user-id');
        
        $this->info("Checking subscriptions expiring in {$daysThreshold} days...");
        
        $now = Carbon::now();
        $expiryDate = $now->copy()->addDays($daysThreshold);
        
        $sentCount = 0;
        $errorCount = 0;
        $skippedCount = 0;

        // Get subscriptions
        $subscriptions = $this->getExpiringSubscriptions($expiryDate, $specificUserId);
        
        $this->info("Found " . count($subscriptions) . " subscriptions expiring soon.");
        
        if (count($subscriptions) === 0) {
            $this->info("No subscriptions found expiring within {$daysThreshold} days.");
            return 0;
        }

        if ($isDryRun) {
            $this->table(
                ['User ID', 'User Name', 'Email', 'Role', 'Subscription Type', 'End Date', 'Days Remaining'],
                $subscriptions->map(function ($sub) {
                    $daysRemaining = Carbon::now()->diffInDays(Carbon::parse($sub->end_date));
                    return [
                        $sub->user_id ?? 'N/A',
                        $sub->user->nama ?? 'N/A',
                        $sub->user->email ?? 'N/A',
                        $sub->user->role == 1 ? 'AJK' : 'Ahli',
                        $sub->getTable(),
                        Carbon::parse($sub->end_date)->format('d/m/Y'),
                        $daysRemaining
                    ];
                })->toArray()
            );
            return 0;
        }

        // Process each subscription
        foreach ($subscriptions as $subscription) {
            try {
                $user = $subscription->user;
                
                if (!$user || !$user->email) {
                    $this->warn("Skipping user ID {$subscription->user_id} - no email found");
                    $skippedCount++;
                    continue;
                }

                // Skip if user already has a newer subscription
                $latestSubscription = $user->getLatestSubscription();
                if ($latestSubscription && $latestSubscription->id !== $subscription->id) {
                    $this->info("Skipping - newer subscription found for user {$user->email}");
                    $skippedCount++;
                    continue;
                }

                // Calculate days remaining
                $daysRemaining = Carbon::now()->diffInDays(Carbon::parse($subscription->end_date));
                
                // Check if we already sent a notification recently (avoid spamming)
                $notificationSentKey = "subscription_expiring_{$subscription->id}_{$daysThreshold}";
                if (cache()->has($notificationSentKey)) {
                    $this->info("Notification already sent for subscription {$subscription->id}");
                    $skippedCount++;
                    continue;
                }

                // Send email
                Mail::to($user->email)->send(new SubscriptionExpiringMail($user, $subscription, $daysRemaining));
                
                // Cache that we sent the notification (prevent duplicate sending)
                cache()->put($notificationSentKey, true, now()->addDays(1));
                
                $this->info("✓ Email sent to: {$user->email} (Days remaining: {$daysRemaining})");
                $sentCount++;
                
                // Log the notification
                Log::info("Subscription expiring notification sent", [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'subscription_id' => $subscription->id,
                    'days_remaining' => $daysRemaining,
                    'end_date' => $subscription->end_date
                ]);
                
            } catch (\Exception $e) {
                $this->error("✗ Failed to send email for subscription ID {$subscription->id}: " . $e->getMessage());
                Log::error("Subscription expiring notification failed", [
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage()
                ]);
                $errorCount++;
            }
        }

        $this->newLine();
        $this->info("=== Summary ===");
        $this->info("Total expiring subscriptions: " . count($subscriptions));
        $this->info("Emails sent: {$sentCount}");
        $this->info("Skipped: {$skippedCount}");
        $this->info("Errors: {$errorCount}");

        return 0;
    }

    /**
     * Get all expiring subscriptions
     */
    private function getExpiringSubscriptions($expiryDate, $specificUserId = null)
    {
        $expiringSubscriptions = collect();

        // Get expiring Kariah subscriptions
        $kariahQuery = SubscriptionsKariah::with('user')
            ->where('status', 'active')
            ->whereDate('end_date', '<=', $expiryDate)
            ->whereDate('end_date', '>=', Carbon::now());
        
        if ($specificUserId) {
            $kariahQuery->where('user_id', $specificUserId);
        }
        
        $kariahSubscriptions = $kariahQuery->get();
        $expiringSubscriptions = $expiringSubscriptions->merge($kariahSubscriptions);

        // Get expiring Masjid subscriptions (AJK)
        $masjidQuery = SubscriptionsMasjid::with('user')
            ->where('status', 'active')
            ->whereDate('end_date', '<=', $expiryDate)
            ->whereDate('end_date', '>=', Carbon::now());
        
        if ($specificUserId) {
            $masjidQuery->where('user_id', $specificUserId);
        }
        
        $masjidSubscriptions = $masjidQuery->get();
        $expiringSubscriptions = $expiringSubscriptions->merge($masjidSubscriptions);

        return $expiringSubscriptions;
    }
}