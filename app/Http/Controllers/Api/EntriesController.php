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

        // fixing api here 
        $keyword = $request->keyword ?? null;

        $words = $keyword ? explode(' ', trim($keyword)) : null;
        if($words)
        {
            $entries = $this->entryModel::where('name', 'LIKE', '%' . $words[0] . '%');
            foreach($words as $key => $word)
            {
                if($key === 0) continue;
                $entries = $entries->orwhere('name', 'LIKE', '%' . $word . '%');
            }
            $entries = $entries->orderBy('created_at', 'DESC');
        }
        $entries = $entries->get();
        $data = [
            'guest_allowed' => $this->guest_allowed,
            'whatsapp' => $this->whatsapp,
            'entries' => $entries
        ];
        
        return $this->responseJson(true, $data, count($entries) . ' entries found', 200);
    }
}