<?php

namespace App\Livewire\Settings;

use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Profile settings')]
class Profile extends Component
{
    public string $name = '';

    public string $code = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->code = Auth::user()->code;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:users,code,'.$user->id],
        ]);

        $user->fill($validated);

        $user->save();

        Flux::toast(variant: 'success', text: __('Profile updated.'));
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        return false;
    }

    #[Computed]
    public function showDeleteUser(): bool
    {
        return true;
    }
}
