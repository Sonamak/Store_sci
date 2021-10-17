<?php

namespace App\Http\Controllers\Api;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Rules\Password;

class AuthColtroller extends Controller {
    private $userModel, $userFieldModel;
    private $registration, $guest_allowed;

    public function __construct(
        User $user,
        UserField $userField
    ) {
        $this->userModel = $user;
        $this->userFieldModel = $userField;

        // Check registration is enabled or not
        $settings = Setting::get();
        
        $guest_allowed = $settings->filter(function($option) {
            return $option->option == 'guest_allowed';
        })->values()[0];
        $registration = $settings->filter(function($option) {
            return $option->option == 'registration';
        })->values()[0];
        
        $this->guest_allowed = $guest_allowed->value == 'on' ? true : false ?? false;
        $this->registration = $registration->value == 'on' ? true : false ?? true;
    }

    // Registration resources
    public function registrationResources() {
        $userFields = $this->userFieldModel
            ->get();
        
        $educational_attainment = $userFields->filter(function($userField) {
            return $userField->field == 'educational_attainment';
        })->values();
        $general_specialization = $userFields->filter(function($userField) {
            return $userField->field == 'general_specialization';
        })->values();

        $countries = DB::table('countries')
            ->select(['id', 'name', 'iso2', 'phonecode'])
            ->orderBy('name', 'ASC')
            ->get();

        $data = [
            'guest_allowed' => $this->guest_allowed,
            'registration_allowed' => $this->registration,
            'educational_attainment' => $educational_attainment,
            'general_specializations' => $general_specialization,
            'countries' => $countries,
        ];

        return $this->responseJson(true, $data, 'Registration resources', 200);
    }

    public function specializations($general_id) {
        $specializations = $this->userFieldModel
            ->with(['parent'])
            ->where('parent_id', $general_id)
            ->get()
            ->makeVisible(['parent_id']);

        return $this->responseJson(true, $specializations, 'Specializations', 200);
    }


    // LOCATIONS
    public function states(Request $request) {
        $country = $request->country ?? null;

        if(empty($country)) {
            return $this->responseJson(false, null, 'Please provide a country name to get the states', 400);
        }

        $states = DB::table('country_states')
            ->select(['id', 'name', 'iso2'])
            ->where('country_code', $country)
            ->orderBy('name', 'ASC')
            ->get();

        if(!count($states)) {
            return $this->responseJson(false, null, 'No state found on this country', 200);
        }

        return $this->responseJson(true, $states, 'States found', 200);
    }

    public function cities(Request $request) {
        $country = $request->country ?? null;
        $state = $request->state ?? null;

        if(empty($country) || empty($state)) {
            return $this->responseJson(false, null, 'Please provide the country and state name to get the cities', 400);
        }

        $cities = DB::table('cities')
            ->select(['id', 'name', 'state_code', 'state_code'])
            ->where('country_code', $country)
            ->where('state_code', $state)
            ->orderBy('name', 'ASC')
            ->get();

        if(!count($cities)) {
            return $this->responseJson(false, null, 'No city found on this state', 200);
        }

        return $this->responseJson(true, $cities, count($cities) . ' Cities found', 200);
    }

    public function register(Request $request) {

        // Validate data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
            'country' => 'required|string',
            'state' => 'nullable|string',
            'city' => 'nullable|string',
            'educational_attainment_id' => 'required|integer',
            'general_specialization_id' => 'required|integer',
            'specialization_id' => 'required|integer',
            'password' => 'required|string|min:8', new Password, 'confirmed',
            'locale' => 'required|string',
        ], [
            'educational_attainment_id.required' => 'Please select an item for Educational Attainment',
            'general_specialization_id.required' => 'Please select an item for General Specialization',
            'specialization_id.required' => 'Please select an item for Specialization',
        ]);

        // Create json messages
        if($validator->fails()) {
            $messages = [];

            foreach($validator->errors()->toArray() as $error) {
                foreach($error as $err) {
                    $messages[] = $err;
                }
            }

            return $this->responseJson(false, $messages,'Registration error', 400);
        }

        // Register
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'country' => $request['country'] ?? null,
            'state' => $request['state'] ?? null,
            'city' => $request['city'] ?? null,
            'educational_attainment_id' => $request['educational_attainment_id'],
            'general_specialization_id' => $request['general_specialization_id'],
            'specialization_id' => $request['specialization_id'],
            'password' => Hash::make($request['password']),
            'locale' => $request['locale'],
            'role' => $request['role'] ?? 'user',
        ])->makeVisible('api_token');

        if(!$user) {
            return $this->responseJson(false, null,'Registration failed', 400);
        }

        return $this->responseJson(true, $user,'Registration success', 200);
    }

    public function login(Request $request) {
        $email = $request->email ?? null;
        $password = $request->password ?? null;

        if(empty($email) || empty($password)) {
            return $this->responseJson(false, null, 'Login credentials are required', 400);
        }

        $user = $this->userModel
            ->withTrashed()
            ->with(['educationalAttainment', 'generalSpecialization', 'specialization'])
            ->where('email', $email)
            ->first()
            ->makeVisible('api_token');

        if(!$user) {
            return $this->responseJson(false, null, 'Email not found', 400);
        }

        if(!empty($user->deleted_at)) {
            return $this->responseJson(false, null, 'Your account has been deleted. Please contact to the admin for more info.', 400);
        }

        if(!Hash::check($password, $user->password)) {
            return $this->responseJson(false, null, 'Invalid password', 400);
        }

        return $this->responseJson(true, $user, 'Login success', 200);
    }

    public function profile(Request $request) {
        $userId = $request->user('api')->id;

        $user = $this->userModel
            ->with(['educationalAttainment', 'generalSpecialization', 'specialization'])
            ->find($userId)
            ->makeVisible('api_token');

        return $this->responseJson(true, $user, 'User profile', 200);
    }

    public function updateProfile(Request $request) {
        $user = $request->user('api');

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['required', 'digits:10', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
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
        ]);

        // Create json messages
        if($validator->fails()) {
            $messages = [];

            foreach($validator->errors()->toArray() as $error) {
                foreach($error as $err) {
                    $messages[] = $err;
                }
            }

            return $this->responseJson(false, $messages,'Profile update error', 400);
        }

        // Update profile
        $user->forceFill([
            'name' => $request['name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'educational_attainment_id' => $request['educational_attainment_id'],
            'general_specialization_id' => $request['general_specialization_id'],
            'specialization_id' => $request['specialization_id'],
            'locale' => $request['locale'],
        ])->save();

        if(!$user) {
            return $this->responseJson(false, null, 'User profile could not be updated', 400);
        }

        return $this->responseJson(true, $user, 'Profile updated', 200);
    }

    public function updatePassword(Request $request) {
        $user = $request->user('api')
            ->makeVisible('api_token');

        $validator = Validator::make($request->all(), [
            'current_password' => ['required', 'string'],
            'password' => new Password,
            'confirm_password' => new Password
        ])->after(function ($validator) use ($user, $request) {
            if (!isset($request['current_password']) || !Hash::check($request['current_password'], $user->password)) {
                $validator->errors()->add('current_password', 'The provided password does not match your current password.');
            }
            if (!isset($request['confirm_password']) || $request['password'] != $request['confirm_password']) {
                $validator->errors()->add('confirm_password', 'Passwords do not match.');
            }
        });

        // Create json messages
        if($validator->fails()) {
            $messages = [];

            foreach($validator->errors()->toArray() as $error) {
                foreach($error as $err) {
                    $messages[] = $err;
                }
            }

            return $this->responseJson(false, $messages,'Password update error', 400);
        }

        // Update password
        $user->forceFill([
            'password' => Hash::make($request['password']),
        ])->save();

        if(!$user) {
            return $this->responseJson(false, null, 'User profile password could not be updated', 400);
        }

        return $this->responseJson(true, $user, 'Profile password updated', 200);
    }
}
