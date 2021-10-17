<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Rules\Password;

class UsersController extends Controller {
    private $userModel;

    public function __construct(User $user) {
        $this->userModel = $user;
    }

    public function updateUser(Request $request, $user_id = null) {
        if(empty($user_id)) {
            return $this->responseJson(false, null, 'Invalid user id', 400);
        }

        $user = User::withTrashed()
            ->find($user_id);

        if(empty($user_id)) {
            return $this->responseJson(false, null, 'No user found', 400);
        }

        // Validate data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|', Rule::unique('users')->ignore($user_id),
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
            'country' => 'required|string',
            'state' => 'nullable|string',
            'city' => 'nullable|string',
            'educational_attainment_id' => 'required|integer',
            'general_specialization_id' => 'required|integer',
            'specialization_id' => 'required|integer',
            'password' => 'nullable|string|min:8', new Password, 'confirmed',
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

            return $this->responseJson(false, $messages, 'User profile update error', 400);
        }

        $data = [
            'name' => $request['name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'country' => $request['country'] ?? null,
            'state' => $request['state'] ?? null,
            'city' => $request['city'] ?? null,
            'educational_attainment_id' => $request['educational_attainment_id'],
            'general_specialization_id' => $request['general_specialization_id'],
            'specialization_id' => $request['specialization_id'],
            'locale' => $request['locale'],
            'role' => $request['role'] ?? 'user',
        ];

        if(isset($request['password']) && !empty($request['password'])) {
            $data['password'] = Hash::make($request['password']);
        }

        // Register
        $user = User::where('id', $user_id)
            ->update($data);

        if(!$user) {
            return $this->responseJson(false, null, 'User profile update failed', 400);
        }

        return $this->responseJson(true, null, 'User profile updated', 200);
    }
}
