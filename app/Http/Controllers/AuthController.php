<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use App\Models\UserField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Auth\Events\Registered;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Contracts\RegisterResponse;
use Illuminate\Contracts\Auth\StatefulGuard;


class AuthController extends Controller {
    private $userModel, $userFieldModel;
    private $registration;
    protected $guard;

    public function __construct(
        User $user,
        UserField $userField,
        StatefulGuard $guard
    ) {
        $this->userModel = $user;
        $this->userFieldModel = $userField;

        // Check registration is enabled or not
        $registration = Setting::where('option', 'registration')->first();
        $this->registration = $registration->value ?? 'off';

        $this->guard = $guard;
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

    public function store(Request $request,
                          CreatesNewUsers $creator): RegisterResponse
    {
        $specialization_id = $request->input('specialization_id');
        $general_specialization_id = $request->input('general_specialization_id');
        event(new Registered($user = $creator->create($request->all())));

        // attach advertisements with userfield related to user.

        $user_fields = UserField::whereIn('id', [$specialization_id, $general_specialization_id])
        ->with('advertisements')
        ->get();

        $advertisements = $user_fields->map(function($user_field){
            return $user_field->advertisements;
        })->reject(function ($user_field) {
            return !empty($user_field->advertisements);
        });
        
        $advertisements = $advertisements->map(function($advertisement){
            return count($advertisement) > 0 ? $advertisement : null;
        })->reject(function ($advertisement, $key) {
            return $advertisement === null;
        });
        
        $ad_ids = $advertisements[1]->map(function($ad){
            return $ad->id;
        })->toArray();

        $user->advertisements()->sync( $ad_ids );
        
        $this->guard->login($user);
        return app(RegisterResponse::class);
    }

    public function registerDisabled() {
        // Redirect if already logged in
        if(Auth::user()) {
            return redirect(route('entries'));
        }
        
        return view('auth.register-disabled');
    }
}