<?php

namespace App\Livewire\UMR;

use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules;

class ManajemenUser extends Component
{
    use WithPagination;

    // TITLE COMPONENT
    #[Title('Manajemen User')]
    public $title = 'Manajemen User';

    // GLOBAL VARIABLES
    public $roles       = [];
    public $units       = [];
    public $perPage     = 5;
    public $search      = '';

    // VARIABLE GLOBAL CREATE USER
    public $user_id, $name, $email, $role, $jabatan, $unit_id, $password, $password_confirmation;
    public $selectedRoles = [];

    // RENDER COMPONENT
    public function render()
    {
        $this->roles = Role::all();

        $this->units = Unit::where('unit_activeStatus', true)->get();

        $users = User::with('unit')->search($this->search)
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);
        
        return view('livewire.pages.umr.manajemen-user.manajemen-user', [
            'users'             => $users,
            'paginationInfo'    => $users->total() > 0
            ? "Showing " . ($users->firstItem()) . " to " . ($users->lastItem()) . " of " . ($users->total()) . " entries"
            : "No entries found",
        ]);
    }

    // SANITIZE INPUTS STORE
    protected function sanitizeInputsStore()
    {
        $this->name           = filter_var($this->name, FILTER_SANITIZE_STRING,  FILTER_FLAG_ALLOW_FRACTION);
        $this->email          = filter_var($this->email, FILTER_SANITIZE_STRING,  FILTER_FLAG_ALLOW_FRACTION);
        $this->jabatan        = filter_var($this->jabatan, FILTER_SANITIZE_STRING,  FILTER_FLAG_ALLOW_FRACTION);
        $this->password       = filter_var($this->password, FILTER_SANITIZE_STRING,  FILTER_FLAG_ALLOW_FRACTION);
    }

    // VALIDATING INPUTS
    protected function validatingInputs()
    {
        $validated = $this->validate([
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'selectedRoles'     => ['required'],
            'unit_id'           => ['required'],
            'jabatan'           => ['required'],
            'password'          => [
                'required', 
                'string', 
                'min:8', 
                'regex:/[A-Z]/',   // at least one uppercase letter
                'regex:/[0-9]|[\W]/', // at least one number or special character
                'confirmed'
            ],
        ],[
            'name.required'             => 'Nama User Wajib di Isi!',
            'name.string'               => 'Nama User harus berupa teks!',
            'name.max'                  => 'Nama User maksimal 255 karakter!',
            
            'email.required'            => 'Email Wajib di Isi!',
            'email.string'              => 'Email harus berupa teks!',
            'email.lowercase'           => 'Email harus dalam huruf kecil!',
            'email.email'               => 'Format Email tidak valid!',
            'email.max'                 => 'Email maksimal 255 karakter!',
            'email.unique'              => 'Email sudah terdaftar!',
            
            'selectedRoles.required'    => 'Peran Wajib di Isi!',
            
            'unit_id.required'          => 'Unit Wajib di Isi!',

            'jabatan.required'          => 'Jabatan Wajib di Isi!',

            'password.required'         => 'Password Wajib di Isi!',
            'password.string'           => 'Password harus berupa teks!',
            'password.min'              => 'Password minimal 8 karakter!',
            'password.regex'            => 'Password harus mengandung setidaknya satu huruf besar dan satu angka atau karakter khusus!',
            'password.confirmed'        => 'Konfirmasi Password tidak sesuai!',
        ]);
    }

    // STORE NEW DATA USER
    public function storeUser()
    {
        // SANITIZE INPUTS
        $this->sanitizeInputsStore();

        // VALIDATING DATA
        $this->validatingInputs();

        // VALIDATE IF RISK OWNER HAS ALREADY ASSIGNED FOR THAT UNIT
        $checkRoleRiskOwner = User::where('unit_id', $this->unit_id)->get();

        $roleExists = false;
        foreach ($checkRoleRiskOwner as $check) {
            $roles = json_decode($check->role, true); // Assuming roles are stored as a JSON array
            if (in_array('risk owner', $roles)) {
                $roleExists = true;
                break;
            }
        }

        if ($roleExists && in_array('risk owner', $this->selectedRoles)) {

            // Return an error message or handle the validation failure

            // close modal
            $this->closeModal();


            // send notification success
            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->error('A Risk Owner has already been assigned for this unit!');
                
        }else{

            // Proceed to store or update the user
            $user = User::updateOrCreate([
                'user_id' => $this->user_id,
            ],
            [
                'unit_id'    => $this->unit_id,
                'name'       => $this->name,
                'email'      => $this->email,
                'jabatan'    => $this->jabatan,
                'role'       => json_encode($this->selectedRoles),
                'password'   => Hash::make($this->password),
                'created_by' => Auth::user()->user_id,
            ]);
    
            // Revoke all existing roles from the user
            $user->syncRoles([]);
    
            // Assign the new roles to the user
            $user->assignRole($this->selectedRoles);
    
            // close modal
            $this->closeModal();
    
            // send notification success
            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->success('Data Anda telah disimpan!');
        }

    }

    // UPDATE DATA USER
    public function editUser($id)
    {
        // OPEN MODAL
        $this->isOpen = true;

        $this->resetForm();
        $this->resetValidation(); // This will clear any validation errors

        $user   = User::find($id);

        // ASSIGN DATA TO VARIABLES
        $this->user_id          = $user->user_id;
        $this->unit_id          = $user->unit_id;
        $this->name             = $user->name;
        $this->email            = $user->email;
        $this->jabatan          = $user->jabatan;
        $this->selectedRoles    = $user->roles->pluck('name')->toArray(); // Assign the user's roles to selectedRoles
    }

    // SORTING DATA USER
    public $orderBy     = 'user_id';
    public $orderAsc    = true;
    public function doSort($column)
    {
        if ($this->orderBy === $column) {
            $this->orderAsc = !$this->orderAsc; // Toggle the sorting order
        } else {
            $this->orderBy = $column;
            $this->orderAsc = true; // Default sorting order when changing the column
        }
    }   

    // ACTIVE OR NON-ACITVE DATA USER
    public bool $isActive = false;
    public function toggleActive($userId)
    {
        // Find the user by user_id
        $user = User::find($userId);
        
        // Toggle the status between 0 and 1 (assuming 0 represents inactive and 1 represents active)
        $user->update(['status' => !$user->status, 'updated_by' => Auth::user()->user_id]);

        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Data Anda telah disimpan!');
    }

    // LIFE CYCLE HOOKS
    // OPEN MODAL LIVEWIRE 
    public $isOpen = 0;
    public function create()
    {
        $this->openModal();
    }

    // OPEN MODAL
    public function openModal()
    {
        $this->resetForm();
        $this->resetValidation(); // This will clear any validation errors

        $this->isOpen = true;
    }

    // CLOSE MODAL
    public function closeModal()
    {
        $this->isOpen = false;

        // Reset form fields and close the modal
        $this->resetForm();

        // Reset form validation
        $this->resetValidation();
    } 

    // CLOSE MODAL
    public function closeXModal()
    {
        $this->isOpen = false;

        // Reset form fields and close the modal
        $this->resetForm();

        // Reset form validation
        $this->resetValidation();
    } 

    // RESET PAGINATION AFTER SEARCH
    public function updatedPerPage()
    {
        $this->resetPage();
    }

    // RESET PAGINATION IF AFTER SEARCH
    public function updatedSearch()
    {
        $this->resetPage();
    }

    // RESET FORM
    public function resetForm()
    {
        $this->user_id                  = '';
        $this->name                     = '';
        $this->email                    = '';
        $this->selectedRoles            = [];
        $this->unit_id                  = '';
        $this->jabatan                  = '';
        $this->password                 = '';
        $this->password_confirmation    = '';
    }

    // CLEAR SEARCH
    public function clearSearch()
    {
        $this->search = '';
    }

}
