<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        // Paksa redirect ke halaman login setelah logout
        $this->redirect('/login', navigate: true);
    }
}; ?>

<div class="hidden">
    <button wire:click="logout" id="real-logout-btn">
        {{ __('Log Out') }}
    </button>
</div>