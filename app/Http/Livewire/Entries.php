<?php

namespace App\Http\Livewire;

use App\Models\Entry;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Entries extends Component {
    use WithPagination;
    use WithFileUploads;
    
    public $showMax = 20, $searchTerm = null, $entryType = null;
    public $entry_id = null, $name = null, $attachment_path = null, $is_private = false;
    public $sortBy, $sortByAsc;
    public $whatsapp = null;

    protected $listeners = [
        'action:delete-entry' => 'deleteEntry',
        'action:restore-entry' => 'restoreEntry',
        'action:force-delete-entry' => 'forceDeleteEntry',
        'action:sort-table' => 'sortBy'
    ];

    protected $rules = [
        'name' => 'required|max:255',
        'is_private' => 'boolean',
    ];

    protected $messages = [
        'name.required' => 'Please type a name for the file.',
    ];

    public function createOrUpdateEntry() {
        if(empty($this->entry_id)) {
            $this->createEntry();
        } else {
            $this->updateEntry();
        }
    }

    public function createEntry() {
        // Check authorization
        if(is_user()) {
            return $this->dispatchBrowserEvent('livewire-event', [
                'success' => false,
                'data' => null,
                'message' => translate('messages.unauthorized')
            ]);
        }

        // Validate
        $validatedData = $this->validate();

        // File is required if not private
        if(!$this->is_private) {
            $this->validate([
                'attachment_path' => 'required|max:6144',
            ], [
                'attachment_path.required' => 'Please select a file to continue.',
                'attachment_path.max' => 'The file should not be more than 6 MB',
            ]);
        }

        try {

            // Upload file
            if(!$this->is_private) {
                $filename = $this->attachment_path->store('attachments');
                $validatedData['attachment_path'] = $filename;
            }

            // Create entry
            Entry::create($validatedData);

            $this->resetFields();
            $this->dispatchBrowserEvent('action:close-modal');
            $this->dispatchBrowserEvent('livewire-event', [
                'success' => true,
                'data' => null,
                'message' => translate('messages.upload_success')
            ]);
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('livewire-event', [
                'success' => false,
                'data' => null,
                'message' => translate('messages.upload_failed'),
            ]);
        }
    }

    public function updateEntry() {
        // Check authorization
        if(is_user()) {
            return $this->dispatchBrowserEvent('livewire-event', [
                'success' => false,
                'data' => null,
                'message' => translate('messages.unauthorized')
            ]);
        }

        // Validate
        $validatedData = $this->validate();

        // Find entry
        $entry = Entry::withTrashed()
            ->find($this->entry_id);

        // Check if file is public but has no file stored
        if(!$this->is_private && empty($entry->attachment_path)) {
            $this->validate([
                'attachment_path' => 'required|max:6144',
            ], [
                'attachment_path.required' => 'Please select a file to continue.',
                'attachment_path.max' => 'The file should not be more than 6 MB',
            ]);
        }
        try {

            // Check if file is uploaded
            if(isset($this->attachment_path)) {                    
                $filename = $this->attachment_path->store('attachments');
                $validatedData['attachment_path'] = $filename;
            }

            // Update entry
            $entry->name = $validatedData['name'];
            $entry->is_private = $validatedData['is_private'];
            if(!empty($validatedData['attachment_path'])) {
                $entry->attachment_path = $validatedData['attachment_path'];
            }

            $entry->save();

            $this->resetFields();
            $this->dispatchBrowserEvent('action:close-modal');
            $this->dispatchBrowserEvent('livewire-event', [
                'success' => true,
                'data' => null,
                'message' => translate('messages.entry_updated')
            ]);
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('livewire-event', [
                'success' => false,
                'data' => null,
                'message' => translate('messages.entry_update_failed'),
            ]);
        }
    }

    public function openEntry($entry_id = null) {
        $this->resetErrorBag();
        
        $entry = Entry::withTrashed()
            ->find($entry_id);

        $this->entry_id = $entry->id ?? null;
        $this->name = $entry->name ?? null;
        $this->is_private = $entry->is_private ?? false;

        $this->dispatchBrowserEvent('loading:entry-loaded');
    }

    public function deleteEntry($entry_id) {
        // Check authorization
        if(is_user()) {
            return $this->dispatchBrowserEvent('livewire-event', [
                'success' => false,
                'data' => null,
                'message' => translate('messages.unauthorized')
            ]);
        }

        // Try to delete entry
        try {
            $entry = Entry::findOrFail($entry_id);

            $entry->delete();

            $this->dispatchBrowserEvent('livewire-event', [
                'success' => true,
                'data' => null,
                'message' => translate('messages.entry_deleted')
            ]);
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('livewire-event', [
                'success' => true,
                'data' => null,
                'message' => translate('messages.entry_could_not_be_deleted')
            ]);
        }
    }

    public function restoreEntry($entry_id) {
        // Check authorization
        if(is_user()) {
            return $this->dispatchBrowserEvent('livewire-event', [
                'success' => false,
                'data' => null,
                'message' => translate('messages.unauthorized')
            ]);
        }

        // Try to restore entry
        try {
            $entry = Entry::withTrashed()
                ->findOrFail($entry_id);

            $entry->restore();

            $this->dispatchBrowserEvent('livewire-event', [
                'success' => true,
                'data' => null,
                'message' => translate('messages.entry_restored')
            ]);
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('livewire-event', [
                'success' => true,
                'data' => null,
                'message' => translate('messages.entry_could_not_be_restored')
            ]);
        }
    }

    public function forceDeleteEntry($entry_id) {
        // Check authorization
        if(is_user()) {
            return $this->dispatchBrowserEvent('livewire-event', [
                'success' => false,
                'data' => null,
                'message' => translate('messages.unauthorized')
            ]);
        }

        // Try to delete entry
        try {
            $entry = Entry::withTrashed()
                ->findOrFail($entry_id);

            Storage::delete($entry->attachment_path);
            $entry->forceDelete();

            $this->dispatchBrowserEvent('livewire-event', [
                'success' => true,
                'data' => null,
                'message' => translate('messages.entry_deleted')
            ]);
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('livewire-event', [
                'success' => true,
                'data' => null,
                'message' => translate('messages.entry_could_not_be_deleted')
            ]);
        }
    }

    public function downloadEntry($entry_id = null) {
        $entry = Entry::find($entry_id);
        $user = User::find(Auth::id());

        if($entry->attachment_url) {
            $entry->download_count = $entry->download_count + 1;
            $user->download_count = $user->download_count + 1;

            $entry->save();
            $user->save();
        }
    }

    public function whatsappEntry($entry_id = null) {
        $entry = Entry::find($entry_id);
        $user = User::find(Auth::id());

        $entry->whatsapp_count = $entry->whatsapp_count + 1;
        $user->whatsapp_count = $user->whatsapp_count + 1;

        $entry->save();
        $user->save();
    }

    public function makeAllPrivate() {
        Entry::query()->update([
            'is_private' => true,
        ]);

        $this->dispatchBrowserEvent('status:privacy-changed');
        $this->dispatchBrowserEvent('livewire-event', [
            'success' => true,
            'data' => null,
            'message' => translate('messages.all_files_private')
        ]);
    }

    public function makeAllPublic() {
        Entry::query()->update([
            'is_private' => false,
        ]);

        $this->dispatchBrowserEvent('status:privacy-changed');
        $this->dispatchBrowserEvent('livewire-event', [
            'success' => true,
            'data' => null,
            'message' => translate('messages.all_files_public')
        ]);
    }

    public function resetFields() {
        $this->entry_id = null;
        $this->name = null;
        $this->attachment_path = null;
        $this->is_private = false;

        $this->dispatchBrowserEvent('action:reset-fields');
    }

    public function sortBy($sortBy, $sortByAsc = true) {
        $this->sortBy = $sortBy;
        $this->sortByAsc = $sortByAsc;
        $this->resetPage();
    }

    public function mount() {
        $whatsapp = Setting::where('option', 'whatsapp')->first();

        $this->whatsapp = $whatsapp->value ?? null;
    }

    public function render() {
        $entries = Entry::withTrashed()
            ->with(['uploader']);

        // Filter deleted entries
        if(!empty($this->entryType)) {
            $entries = $entries->where('deleted_at', '!=', null);
        } else {
            $entries = $entries->where('deleted_at', '=', null);
        }

        // Search
        if(!empty($this->searchTerm)) {
            $entries = $entries->where(function($query) {
                return $query->where('id', $this->searchTerm)
                    ->orWhere('name', 'LIKE', '%' . str_replace(' ', '% %', $this->searchTerm) . '%')
                    ->orWhereHas('uploader', function($user) {
                        return $user->where('name', 'LIKE', '%' . $this->searchTerm . '%');
                    });
            });

            // Set page to 1 if searching
            $this->page = 1;
        }

        // Sort By
        if(!empty($this->sortBy)) {
            $entries->orderBy($this->sortBy, $this->sortByAsc ? 'ASC' : 'DESC');
        } else {
            $entries->orderBy('created_at', 'DESC');
        }

        $data = [
            'total_entries' => $entries->count(),
            'entries' => $entries->paginate($this->showMax),
        ];

        return view('livewire.entries', $data);
    }
}
