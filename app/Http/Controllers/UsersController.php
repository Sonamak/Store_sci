<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller {
    private $userModel;

    public function __construct(User $user) {
        $this->userModel = $user;
    }

    public function index() {
        return view('dashboard.users');
    }

    public function createUser() {
        // If enabled, show register page
        $userFields = UserField::get();
        
        $educational_attainment = $userFields->filter(function($userField) {
            return $userField->field == 'educational_attainment';
        });
        $general_specialization = $userFields->filter(function($userField) {
            return $userField->field == 'general_specialization';
        });
        $specialization = $userFields->filter(function($userField) {
            return $userField->field == 'specialization';
        });

        $countries = DB::table('countries')
            ->select(['name', 'iso2', 'phonecode'])
            ->orderBy('name', 'ASC')
            ->get();

        $data = [
            'educational_attainment' => $educational_attainment,
            'general_specialization' => $general_specialization,
            'specialization' => $specialization,
            'countries' => $countries,
        ];

        return view('dashboard.create-user', $data);
    }

    public function editUser($locale, $user_id) {
        $user = User::withTrashed()
            ->findOrFail($user_id);
        
        $educational_attainment = UserField::where('field', 'educational_attainment')
            ->get();

        $general_specialization = UserField::where('field', 'general_specialization')
            ->get();

        $specialization = UserField::where('field', 'specialization')
            ->where('parent_id', $user->general_specialization_id)
            ->get();

        $countries = DB::table('countries')
            ->select(['name', 'iso2', 'phonecode'])
            ->orderBy('name', 'ASC')
            ->get();

        $states = DB::table('country_states')
            ->select(['name', 'iso2'])
            ->where('country_code', $user->country)
            ->orderBy('name', 'ASC')
            ->get();
        
        $cities = DB::table('cities')
            ->select(['name', 'state_code', 'state_code'])
            ->where('country_code', $user->country)
            ->where('state_code', $user->state)
            ->orderBy('name', 'ASC')
            ->get();

        $data = [
            'educational_attainment' => $educational_attainment,
            'general_specialization' => $general_specialization,
            'specialization' => $specialization,
            'countries' => $countries,
            'states' => $states,
            'cities' => $cities,
            'user' => $user,
        ];
        return view('dashboard.edit-user', $data);
    }

    public function userFields() {
        return view('dashboard.user-fields');
    }
}
