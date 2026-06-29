<?php

namespace App\Services;

use Carbon\Carbon;

class MembershipService
{
    public function getStartDate(?Carbon $date = null): Carbon
    {
        return $date ?? now();
    }

    public function getEndDate(?Carbon $date = null): Carbon
    {
        return ($date ?? now())
            ->copy()
            ->endOfYear()
            ->endOfDay();
    }

    public function renewalEndDate(Carbon $currentEndDate): Carbon
    {
        return Carbon::create(
            $currentEndDate->year + 1,
            1,
            31
        )->endOfDay();
    }

    public function isExpired(Carbon $endDate): bool
    {
        return now()->greaterThan($endDate);
    }

    public function daysRemaining(Carbon $endDate): int
    {
        return max(0, now()->diffInDays($endDate, false));
    }
}
