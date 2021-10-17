<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Entry;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EntriesController extends Controller {
    private $entryModel;
    private $whatsapp, $guest_allowed;

    public function __construct(Entry $entry) {
        $this->entryModel = $entry;

        // Settings
        $whatsapp = Setting::where('option', 'whatsapp')->first();
        $guest_allowed = Setting::where('option', 'guest_allowed')->first();

        $this->whatsapp = $whatsapp->value ?? null;
        $this->guest_allowed = $guest_allowed->value == 'on' ? true : false ?? false;
    }

    public function index() {
        $data = [
            'guest_allowed' => $this->guest_allowed,
            'whatsapp' => $this->whatsapp,
            'entries' => $this->entryModel
                ->with(['uploader'])
                ->get()
        ];

        return $this->responseJson(true, $data, 'Entries found', 200);
    }

    public function search(Request $request) {
        $keyword = $request->keyword ?? null;

        $entries = $this->entryModel
            ->where('name', 'LIKE',  '%' . str_replace(' ', '% %', $keyword) . '%')
            ->get();

        $data = [
            'guest_allowed' => $this->guest_allowed,
            'whatsapp' => $this->whatsapp,
            'entries' => $entries
        ];

        return $this->responseJson(true, $data, count($entries) . ' entries found', 200);
    }
}
