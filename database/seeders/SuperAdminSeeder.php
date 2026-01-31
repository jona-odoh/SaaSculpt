<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Laravel\Jetstream\Jetstream;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = config('app.super_admins')[0] ?? 'admin@saasculpt.test';
        
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Ensure user has a personal team (Jetstream requirement)
        if (Jetstream::hasTeamFeatures() && !$user->personalTeam()) {
            $user->ownedTeams()->save(Jetstream::newTeamModel()->forceFill([
                'user_id' => $user->id,
                'name' => explode(' ', $user->name, 2)[0]."'s Team",
                'slug' => \Illuminate\Support\Str::slug(explode(' ', $user->name, 2)[0]."'s Team"),
                'personal_team' => true,
            ]));
        }
    }
}
