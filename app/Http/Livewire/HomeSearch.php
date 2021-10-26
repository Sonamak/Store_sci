<?php

namespace App\Http\Livewire;

use App\Models\Entry;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class HomeSearch extends Component {
    use WithPagination;
    
    public $searchTerm = '', $showMax = 10;
    public $whatsapp, $guestAllowed;

    public function downloadEntry($entry_id = null) {
        $entry = Entry::find($entry_id);
        $user = User::find(Auth::id());
        

        if($entry->attachment_url) {
            $entry->download_count = $entry->download_count + 1;
            $entry->save();

            if($user) {
                $user->download_count = $user->download_count + 1;
                $user->save();
            }
        }
    }

    public function whatsappEntry($entry_id = null) {
        $entry = Entry::find($entry_id);
        $user = User::find(Auth::id());

        $entry->whatsapp_count = $entry->whatsapp_count + 1;
        $entry->save();

        if($user) {
            $user->whatsapp_count = $user->whatsapp_count + 1;
            $user->save();
        }
    }

    public function mount() {
        $whatsapp = Setting::where('option', 'whatsapp')->first();
        $guest_allowed = Setting::where('option', 'guest_allowed')->first();

        $this->whatsapp = $whatsapp->value ?? null;

        if(Auth::user()) {
            $this->guestAllowed = true;
        } else {
            $this->guestAllowed = $guest_allowed->value == 'on' ? true : false ?? false;
        }
    }

    public function render() {
        $entries = [];

        if(!empty($this->searchTerm) && strlen($this->searchTerm) >= 3) {
            $words = explode(' ', trim($this->searchTerm));
            $entries = Entry::where('name', 'LIKE', '%' . $words[0] . '%');

            foreach($words as $key => $word)
            {
                if($key === 0) continue;
                $entries = $entries->orwhere('name', 'LIKE', '%' . $word . '%');
            }
            $entries = $entries->orderBy('created_at', 'DESC');

            $entriesCount = $entries->count() ?? 0;
            $entries = $entries->paginate($this->showMax);
        }

        $data = [
            'total_entries' => $entriesCount ?? 0,
            'entries' => $entries,
        ];

        $this->dispatchBrowserEvent('load:search-finish');

        return view('livewire.home-search', $data);
    }
}
