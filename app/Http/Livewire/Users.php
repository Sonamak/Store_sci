<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component {
    use WithPagination;
    
    public $showMax = 20, $searchTerm = null, $accountType = null;

    protected $listeners = [
        'action:block-user' => 'blockUser',
        'action:delete-user' => 'deleteUser',
        'action:restore-user' => 'restoreUser',
        'action:sort-table' => 'sortBy'
    ];

    protected $rules = [
        'name' => 'required|max:255',
    ];

    protected $messages = [
        'name.required' => 'Please type the user\'s name.',
    ];

    public function blockUser($user_id) {
        // Check authorization
        if(is_user()) {
            return $this->dispatchBrowserEvent('livewire-event', [
                'success' => false,
                'data' => null,
                'message' => translate('messages.unauthorized')
            ]);
        }

        // Try to soft delete user
        try {
            $user = User::findOrFail($user_id);

            $user->delete();

            $this->dispatchBrowserEvent('livewire-event', [
                'success' => true,
                'data' => null,
                'message' => translate('messages.user_blocked')
            ]);
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('livewire-event', [
                'success' => true,
                'data' => null,
                'message' => translate('messages.user_could_not_be_blocked')
            ]);
        }
    }

    public function restoreUser($user_id) {
        // Check authorization
        if(is_user()) {
            return $this->dispatchBrowserEvent('livewire-event', [
                'success' => false,
                'data' => null,
                'message' => translate('messages.unauthorized')
            ]);
        }

        // Try to restore user
        try {
            $user = User::withTrashed()
                ->findOrFail($user_id);

            $user->restore();

            $this->dispatchBrowserEvent('livewire-event', [
                'success' => true,
                'data' => null,
                'message' => translate('messages.user_restored')
            ]);
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('livewire-event', [
                'success' => true,
                'data' => null,
                'message' => translate('messages.user_could_not_be_restored')
            ]);
        }
    }

    public function deleteUser($user_id) {
        // Check authorization
        if(is_user()) {
            return $this->dispatchBrowserEvent('livewire-event', [
                'success' => false,
                'data' => null,
                'message' => translate('messages.unauthorized')
            ]);
        }

        // Try to delete user
        try {
            $user = User::withTrashed()
                ->findOrFail($user_id);

            $user->forceDelete();

            $this->dispatchBrowserEvent('livewire-event', [
                'success' => true,
                'data' => null,
                'message' => translate('messages.user_deleted')
            ]);
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('livewire-event', [
                'success' => true,
                'data' => null,
                'message' => translate('messages.user_could_not_be_deleted')
            ]);
        }
    }

    public function sortBy($sortBy, $sortByAsc = true) {
        $this->sortBy = $sortBy;
        $this->sortByAsc = $sortByAsc;
        $this->resetPage();
    }

    public function mount() {}
    
    public function render() {
        $users = User::withTrashed()
            ->with(['educationalAttainment', 'generalSpecialization', 'specialization']);
            // ->where('id', '!=', Auth::id());

        // Filter deleted users
        if(!empty($this->accountType)) {
            $users = $users->where('deleted_at', '!=', null);
        } else {
            $users = $users->where('deleted_at', '=', null);
        }

        // Search
        if(!empty($this->searchTerm)) {
            $users = $users->where(function($query) {
                return $query->where('id', $this->searchTerm)
                    ->orWhere('name', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('name', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('email', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('phone', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('country', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('city', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('role', 'LIKE', '%' . $this->searchTerm . '%');
            });

            // Set page to 1 if searching
            $this->page = 1;
        }

        // Sort By
        if(!empty($this->sortBy)) {
            $users->orderBy($this->sortBy, $this->sortByAsc ? 'ASC' : 'DESC');
        } else {
            $users->orderBy('created_at', 'ASC');
        }

        $data = [
            'total_users' => $users->count(),
            'users' => $users->paginate($this->showMax),
        ];

        return view('livewire.users', $data);
    }
}
