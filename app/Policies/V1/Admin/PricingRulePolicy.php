<?php

namespace App\Policies\V1\Admin;

use App\Models\PricingRule;
use App\Models\User;

class PricingRulePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_admin;
    }

    public function view(User $user, PricingRule $pricingRule): bool
    {
        return $user->is_admin;
    }

    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    public function update(User $user, PricingRule $pricingRule): bool
    {
        return $user->is_admin;
    }

    public function delete(User $user, PricingRule $pricingRule): bool
    {
        return $user->is_admin;
    }
}
