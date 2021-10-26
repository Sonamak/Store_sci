<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller {
    private $storage;

    public function __construct() {
        $this->storage = Storage::disk('local');
    }

    public function backup() {
        // $time = date('Y-m-d-H-i-s', time());
        // $filename = 'backups/' . $time . '.zip';

        Artisan::call('backup:run');
        dd("A");
        return $this->storage->download($filename);
    }

    public function restore(Request $request) {

    }
}
