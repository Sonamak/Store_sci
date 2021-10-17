<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use Illuminate\Http\Request;
use App\Exports\EntriesExport;


use App\Imports\EntriesImport;
use Maatwebsite\Excel\Facades\Excel;

class EntriesController extends Controller {
    private $entryModel;

    public function __construct(Entry $entry) {
        $this->entryModel = $entry;
    }

    public function index() {
        return view('dashboard.entries');
    }

    public function exportCsv() {
        $fileName = 'entries-' . time() . '.csv';
        $entries = Entry::withTrashed()
            ->get();

        $columns = \Schema::getColumnListing('entries');
        
        $csvExporter = new \Laracsv\Export();
        $csvExporter->build($entries, $columns)->download($fileName);
    }

    public function importCsv() {
        // Excel::import(new EntriesImport, 'entries.xlsx');
        // return redirect('/')->with('success', 'All good!');
    }

    public function testing(Request $request)
    {
        $path = $request->file('file')->store('testing');
        Excel::import(new EntriesImport, $path);
        return redirect()->back();
    }
}