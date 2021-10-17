<?php

namespace App\Actions\Fortify;

use App\Models\UserField;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    public function update($user, array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
            'educational_attainment_id' => ['required', 'integer', 'exists:user_fields,id'],
            'general_specialization_id' => ['required', 'integer', 'exists:user_fields,id'],
            'specialization_id' => ['required', 'integer', 'exists:user_fields,id'],
            'locale' => ['required', 'string'],
        ], [
            'educational_attainment_id.required' => 'Please select an item for Educational Attainment',
            'educational_attainment_id.exists' => 'Please select a valid option for Educational Attainment',
            'general_specialization_id.required' => 'Please select an item for General Specialization',
            'general_specialization_id.exists' => 'Please select a valid option for General Specialization',
            'specialization_id.required' => 'Please select an item for Specialization',
            'specialization_id.exists' => 'Please select a valid option for Specialization',
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'name' => $input['name'],
                'email' => $input['email'],
                'phone' => $input['phone'],
                'educational_attainment_id' => $input['educational_attainment_id'],
                'general_specialization_id' => $input['general_specialization_id'],
                'specialization_id' => $input['specialization_id'],
                'locale' => $input['locale'],
            ])->save();

            return redirect(route('profile.show'));
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    protected function updateVerifiedUser($user, array $input)
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
