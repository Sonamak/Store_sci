<?php

use Illuminate\Support\Facades\Storage;

if(!function_exists('lang')) {
    function lang() {
        return app()->getLocale() == 'en' ? 'en_US' : 'ar_AR';
    }
}

if(!function_exists('translate')) {
    function translate($string) {
        $string = explode('.', $string);
        $val = [];

        // Get file
        $array = include base_path() . '/lang/' . lang() . '/' . $string[0] . '.lang.php';

        // Get the variable inside the file
        $variable = $string[count($string) - 1];

        array_walk_recursive($array, function($v, $k) use($variable, &$val){
            if($k == $variable) array_push($val, $v);
        });

        return count($val) > 1 ? $val[0] : array_pop($val);
    }
}

if(!function_exists('avatar')) {
    function avatar($user, $customAvatar = false, $length = 2, $fontSize = 0.5) {
        if(!isset($user) || $customAvatar) {
            return "https://ui-avatars.com/api/?name=$user&font-size=$fontSize&format=svg&color=F9FAFB&background=9061f9&length=$length";
        }

        if(empty($user->profile_photo_path)) {
            $name = implode('+', explode(' ', $user->name));

            return "https://ui-avatars.com/api/?name=$name&font-size=$fontSize&format=svg&color=F9FAFB&background=9061f9&length=$length";
        }

        return $user->profile_photo_url;
    }
}

if(!function_exists('is_admin')) {
    function is_admin($user = null) {
        if(empty($user) && empty(auth()->user())) {
            return false;
        }
        if(isset($user) && !empty($user)) {
            return $user->role == 'admin' ? true : false;
        }

        return auth()->user()->role == 'admin' ? true : false;
    }
}

if(!function_exists('is_supervisor')) {
    function is_supervisor($user = null) {
        if(empty($user) && empty(auth()->user())) {
            return false;
        }
        if(isset($user) && !empty($user)) {
            return $user->role == 'supervisor' ? true : false;
        }

        return auth()->user()->role == 'supervisor' ? true : false;
    }
}

if(!function_exists('is_user')) {
    function is_user($user = null) {
        if(empty($user) && empty(auth()->user())) {
            return false;
        }
        if(isset($user) && !empty($user)) {
            return $user->role == 'user' ? true : false;
        }

        return auth()->user()->role == 'user' ? true : false;
    }
}

if(!function_exists('delete_attachment')) {
    function delete_attachment($file = null) {
        if(!empty($file) && Storage::exists($file)) {
            Storage::delete($file);
        }
    }
}