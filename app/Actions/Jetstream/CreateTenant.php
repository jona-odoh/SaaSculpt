<?php

namespace App\Actions\Jetstream;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Contracts\CreatesTeams;
use Laravel\Jetstream\Events\AddingTeam;
use Laravel\Jetstream\Jetstream;

class CreateTenant implements CreatesTeams
{
    /**
     * Validate and create a new team for the given user.
     *
     * @param  array<string, string>  $input
     */
    public function create(User $user, array $input): Tenant
    {
        Gate::forUser($user)->authorize('create', Jetstream::newTeamModel());

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
        ])->validateWithBag('createTeam');

        return tap(new Tenant, function ($tenant) use ($user, $input) {
            $slug = $input['slug'] ?? \Illuminate\Support\Str::slug($input['name']);

            // Fetch Plan
            $plan = \App\Models\Plan::where('slug', $input['plan_slug'] ?? 'free')->first();
            // Fallback to first available plan if 'free' doesn't exist, or handle gracefully
            if (!$plan) {
                 $plan = \App\Models\Plan::first();
            }

            $tenant->forceFill([
                'user_id' => $user->id,
                'name' => $input['name'],
                'slug' => $slug,
                'personal_team' => false,
                'plan_id' => $plan?->id,
            ])->save();

            $user->teams()->attach($tenant, ['role' => 'admin']);
            $user->switchTeam($tenant);
        });
    }
}
