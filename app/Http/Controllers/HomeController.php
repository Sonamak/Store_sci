<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class HomeController extends Controller {

    public function index(Request $request) {
        return view('landing.home');
    }

    public function privacyPolicy(Request $request) {
        return view('landing.privacy-policy-' . lang());
    }

    public function setLocale(Request $request) {
        $locale = $request->locale ?? null;
        $redirect = $request->get('redirect') ?? 'home';
        
        app()->setLocale($locale == 'ar_AR' ? 'ar' : 'en');

        if(!empty($redirect) && Route::has($redirect)) {
            return redirect(route($redirect, app()->getLocale()));
        } else {
            return redirect(route('home', app()->getLocale()));
        }
    }
}
