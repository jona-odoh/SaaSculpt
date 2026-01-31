<?php

namespace App\Actions\Fortify;


use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'company_name' => ['required', 'string', 'max:255'],
            'company_slug' => ['required', 'string', 'max:255', 'unique:tenants,slug', 'regex:/^[a-zA-Z0-9\-]+$/'],
            'plan' => ['nullable', 'string', 'exists:plans,slug'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        return DB::transaction(function () use ($input) {
            return tap(User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]), function (User $user) use ($input) {
                $this->createTeam($user, $input);
            });
        });
    }

    /**
     * Create a personal team for the user.
     */
    protected function createTeam(User $user, array $input): void
    {
        // Use the CreateTenant action to create the organization
        $creator = new \App\Actions\Jetstream\CreateTenant();
        
        $creator->create($user, [
            'name' => $input['company_name'],
            'slug' => $input['company_slug'],
            'plan_slug' => $input['plan'] ?? 'free',
        ]);
    }
}
