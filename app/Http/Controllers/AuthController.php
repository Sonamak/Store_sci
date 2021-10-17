<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use App\Models\UserField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller {
    private $userModel, $userFieldModel;
    private $registration;

    public function __construct(
        User $user,
        UserField $userField
    ) {
        $this->userModel = $user;
        $this->userFieldModel = $userField;

        // Check registration is enabled or not
        $registration = Setting::where('option', 'registration')->first();
        $this->registration = $registration->value ?? 'off';
    }

    public function register() {
        // Redirect if already logged in
        if(Auth::user()) {
            return redirect(route('entries'));
        }

        // Check registration is enabled or not
        if($this->registration == 'off') {
            return redirect(route('register_disabled'));
        }

        // If enabled, show register page
        $userFields = $this->userFieldModel
            ->get();
        
        $educational_attainment = $userFields->filter(function($userField) {
            return $userField->field == 'educational_attainment';
        });
        $general_specialization = $userFields->filter(function($userField) {
            return $userField->field == 'general_specialization';
        });

        $countries = DB::table('countries')
            ->select(['name', 'iso2', 'phonecode'])
            ->orderBy('name', 'ASC')
            ->get();

        $data = [
            'educational_attainment' => $educational_attainment,
            'general_specialization' => $general_specialization,
            'countries' => $countries,
        ];

        return view('auth.register', $data);
    }

    public function registerDisabled() {
        // Redirect if already logged in
        if(Auth::user()) {
            return redirect(route('entries'));
        }
        
        return view('auth.register-disabled');
    }
}