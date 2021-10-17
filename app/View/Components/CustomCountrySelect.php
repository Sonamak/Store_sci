<?php

namespace App\View\Components;

use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class CustomCountrySelect extends Component {
    
    public $name, $id, $countries, $required, $width, $selected = null;

    /**
     * Create a new component instance.
     *
     * @return void
     */

    public function __construct(
        $name = null,
        $id = null,
        $countries = null,
        $required = false,
        $width = null,
        $selected = null
    ) {

        $this->name = $name;
        $this->id = $id;
        $this->countries = $countries;
        $this->required = $required;
        $this->width = $width;

        if(!empty($selected)) {
            $this->selected = DB::table('countries')
                ->select(['name', 'iso2', 'phonecode'])
                ->where('iso2', '=', $selected)
                ->first();
        }
    }
    
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render() {
        return view('components.custom-country-select');
    }
}
