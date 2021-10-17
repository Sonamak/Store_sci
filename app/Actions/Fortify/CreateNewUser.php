<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'country' => ['required', 'string'],
            'state' => ['nullable', 'string'],
            'city' => ['nullable', 'string'],
            'educational_attainment_id' => ['required', 'integer'],
            'general_specialization_id' => ['required', 'integer'],
            'specialization_id' => ['required', 'integer'],
            'password' => $this->passwordRules(),
            'locale' => ['required', 'string'],
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'phone' => $input['phone'],
            'country' => $input['country'],
            'state' => $input['state'] ?? null,
            'city' => $input['city'] ?? null,
            'educational_attainment_id' => $input['educational_attainment_id'],
            'general_specialization_id' => $input['general_specialization_id'],
            'specialization_id' => $input['specialization_id'],
            'password' => Hash::make($input['password']),
            'locale' => $input['locale'],
            'role' => $input['role'] ?? 'user',
        ]);
    }
}
