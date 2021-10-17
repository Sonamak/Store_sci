<?php

namespace App\View\Components;

use Illuminate\View\Component;

class LandingLayout extends Component {
    public $title;
    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */

    public function __construct($title = null) {
        $this->title = $title ?? ucwords(str_replace(['_', '.'], ' ', \Illuminate\Support\Facades\Route::currentRouteName()));
    }

    public function render() {
        return view('layouts.landing');
    }
}
