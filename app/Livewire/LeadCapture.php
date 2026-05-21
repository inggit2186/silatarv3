<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class LeadCapture extends Component
{
    public string $name = '';

    public string $email = '';

    public string $company = '';

    public string $message = '';

    public bool $submitted = false;

    protected array $rules = [
        'name' => ['required', 'string', 'min:2', 'max:80'],
        'email' => ['required', 'email:rfc,dns', 'max:120'],
        'company' => ['nullable', 'string', 'max:120'],
        'message' => ['nullable', 'string', 'max:500'],
    ];

    public function submit(): void
    {
        $this->validate();

        $this->submitted = true;
        $this->reset(['name', 'email', 'company', 'message']);
    }

    public function render(): View
    {
        return view('livewire.lead-capture');
    }
}
