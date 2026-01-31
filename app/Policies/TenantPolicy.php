<?php

namespace App\Policies;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TenantPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Tenant $tenant): bool
    {
        return $user->belongsToTeam($tenant);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Tenant $tenant): bool
    {
        return $user->ownsTeam($tenant);
    }

    /**
     * Determine whether the user can add team members.
     */
    public function addTeamMember(User $user, Tenant $tenant): bool
    {
        return $user->ownsTeam($tenant);
    }

    /**
     * Determine whether the user can update team member permissions.
     */
    public function updateTeamMember(User $user, Tenant $tenant): bool
    {
        return $user->ownsTeam($tenant);
    }

    /**
     * Determine whether the user can remove team members.
     */
    public function removeTeamMember(User $user, Tenant $tenant): bool
    {
        return $user->ownsTeam($tenant);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Tenant $tenant): bool
    {
        return $user->ownsTeam($tenant);
    }
}
