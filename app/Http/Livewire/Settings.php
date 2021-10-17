<?php

namespace App\Http\Livewire;

use App\Models\Setting;
use Livewire\Component;

class Settings extends Component {
    
    public $data = [
        'whatsapp' => null,
        'registration' => 'off',
        'guest_allowed' => 'on',
    ];

    public function saveSettings() {
        foreach($this->data as $key => $row) {
            Setting::where('option', $key)
                ->update([
                    'value' => $row
                ]);
        }

        $this->dispatchBrowserEvent('livewire-event', [
            'success' => true,
            'data' => null,
            'message' => translate('messages.settings_saved'),
        ]);
    }

    public function load() {
        $settings = Setting::get();

        // Data
        $whatsapp = $settings->filter(function($option) {
            return $option->option == 'whatsapp';
        })->values()[0];
        $registration = $settings->filter(function($option) {
            return $option->option == 'registration';
        })->values()[0];
        $guest_allowed = $settings->filter(function($option) {
            return $option->option == 'guest_allowed';
        })->values()[0];

        // Set data
        $this->data['whatsapp'] = $whatsapp->value ?? null;
        $this->data['registration'] = $registration->value ?? 'off';
        $this->data['guest_allowed'] = $guest_allowed->value ?? 'on';
    }

    public function render() {
        $this->load();

        return view('livewire.settings');
    }
}
