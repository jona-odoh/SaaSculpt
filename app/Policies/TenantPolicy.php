<?php

namespace App\Policies;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TenantPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

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
        return $user->ownsTeam($tenant) || $user->hasTeamPermission($tenant, 'update');
    }

    /**
     * Determine whether the user can add team members.
     * Restricted to Admins (who have 'delete' permission) or the Owner.
     */
    public function addTeamMember(User $user, Tenant $tenant): bool
    {
        return $user->ownsTeam($tenant) || $user->hasTeamPermission($tenant, 'delete');
    }

    /**
     * Determine whether the user can update team member permissions.
     * Restricted to Admins or the Owner.
     */
    public function updateTeamMember(User $user, Tenant $tenant): bool
    {
        return $user->ownsTeam($tenant) || $user->hasTeamPermission($tenant, 'delete');
    }

    /**
     * Determine whether the user can remove team members.
     * Restricted to Admins or the Owner.
     */
    public function removeTeamMember(User $user, Tenant $tenant): bool
    {
        return $user->ownsTeam($tenant) || $user->hasTeamPermission($tenant, 'delete');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Tenant $tenant): bool
    {
        return $user->ownsTeam($tenant);
    }
}
