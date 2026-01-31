<?php

namespace App\Providers;

use App\Models\Tenant;
use App\Models\User;
use App\Policies\TenantPolicy;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configurePermissions();

        Jetstream::useTeamModel(Tenant::class);

        Jetstream::createTeamsUsing(function (User $user, string $name) {
            $tenant = new Tenant;
            // Generate slug from name
            $slug = \Illuminate\Support\Str::slug($name);
            // Ensure unique slug... (basic logic)
            // In production, we'd check DB loop
            
            $tenant->forceFill([
                'user_id' => $user->id,
                'name' => $name,
                'slug' => $slug,
                'personal_team' => false,
            ])->save();
            
            $user->teams()->attach($tenant, ['role' => 'admin']);

            return $tenant;
        });
    }

    /**
     * Configure the permissions that are available within the application.
     */
    protected function configurePermissions(): void
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::permissions([
            'create',
            'read',
            'update',
            'delete',
        ]);

        Jetstream::role('admin', 'Administrator', [
            'create',
            'read',
            'update',
            'delete',
        ])->description('Administrator users can perform any action.');

        Jetstream::role('member', 'Member', [
            'read',
            'create',
            'update',
        ])->description('Editor users have the ability to read, create, and update.');
    }
}
