<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller {
    private $settingsModel;

    public function __construct(Setting $setting) {
        $this->settingModel = $setting;
    }

    public function index() {
        return view('dashboard.settings');
    }
}