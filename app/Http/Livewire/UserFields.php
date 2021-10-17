<?php

namespace App\Http\Livewire;

use App\Models\UserField;
use Livewire\Component;
use Livewire\WithPagination;

class UserFields extends Component {
    use WithPagination;
    
    public $showMax = 5, $searchTerm = null;
    public $field = null, $fieldName = null;
    public $row_id = null, $label = null, $parent_id = '';

    protected $listeners = [
        'action:delete-row' => 'deleteRow',
        'action:sort-table' => 'sortBy'
    ];

    protected $rules = [
        'label' => 'required|max:255',
    ];

    protected $messages = [
        'label.required' => 'Please enter a label for the type.',
    ];

    public function createOrUpdateRow() {
        if(empty($this->row_id)) {
            $this->createRow();
        } else {
            $this->updateRow();
        }
    }

    public function createRow() {
        // Validate
        $validatedData = $this->validate();

        try {
            $validatedData['field'] = $this->field;

            // Change parent id
            if($this->field == 'specialization') {
                $validatedData['parent_id'] = $this->parent_id;
            }

            // Create row
            UserField::create($validatedData);

            $this->resetFields();
            $this->dispatchBrowserEvent('action:close-modal');
            $this->dispatchBrowserEvent('livewire-event', [
                'success' => true,
                'data' => null,
                'message' => translate('messages.entry_created')
            ]);
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('livewire-event', [
                'success' => false,
                'data' => null,
                'message' => translate('messages.entry_create_failed'),
            ]);
        }
    }

    public function updateRow() {
        // Validate
        $validatedData = $this->validate();

        // Find entry
        $data = UserField::find($this->row_id);

        try {
            // Update row
            $data->label = $validatedData['label'];

            // Change parent id
            if($this->field == 'specialization') {
                $data->parent_id = $this->parent_id;
            }

            // Save data
            $data->save();

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

    public function openRow($row_id = null) {
        $this->resetErrorBag();
        
        $data = UserField::find($row_id);

        $this->row_id = $data->id ?? null;
        $this->label = $data->label ?? null;
        $this->parent_id = $data->parent_id ?? '';

        $this->dispatchBrowserEvent('loading:row-loaded');
    }

    public function deleteRow($row_id, $field) {
        if($this->field == $field) {
            // Try to delete entry
            try {
                $entry = UserField::findOrFail($row_id);

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
    }

    public function onChangeShowMax($showMax) {
        $this->showMax = $showMax;
        $this->page = 1;
    }

    public function resetFields() {
        $this->row_id = null;
        $this->label = null;

        $this->dispatchBrowserEvent('action:reset-fields');
    }

    public function mount($field = null, $id = null) {
        if(empty($field)) {
            dd('Field name is required.');
        }

        $this->field = $field;
        $this->fieldName = ucwords(str_replace(['_', '.'], ' ', $field));

        $this->page = 1;
    }

    public function render() {
        $all_rows = UserField::where('field', '=', $this->field);

        // Get general Specializations if it's child
        if($this->field == 'specialization') {
            $general_specializations = UserField::where('field', '=', 'general_specialization')->get();
        }

        // Search
        if(!empty($this->searchTerm) && strlen($this->searchTerm) > 2) {
            $all_rows = $all_rows->where('label', 'LIKE', '%' . $this->searchTerm . '%');

            // Set page to 1 if searching
            $this->page = 1;
        }

        $data = [
            'total_rows' => $all_rows->count(),
            'all_rows' => $all_rows->paginate($this->showMax),
            
        ];

        if($this->field == 'specialization') {
            $data['general_specializations'] = $general_specializations ?? null;
        }

        return view('livewire.user-fields', $data);
    }
}
