<?php

namespace App\Livewire\Settings;

use Livewire\Component;

class SettingsManager extends Component
{
    public function toggleLanguage()
    {
        $user = auth()->user();
        $user->locale = $user->locale === 'en' ? 'id' : 'en';
        $user->save();

        // Refresh the page so the locale change takes effect
        $this->redirect(route('settings'), navigate: true);
    }

    public function render()
    {
        return view('livewire.settings.settings-manager');
    }
}
