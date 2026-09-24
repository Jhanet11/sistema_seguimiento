<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return $this->user()->esCliente() ? ['nombre' => ['required', 'string', 'max:255']] : ['nombre' => ['required', 'string', 'max:255'], 'email' => ['required', 'email', 'max:255', Rule::unique('usuarios', 'email')->ignore($this->user()->id)]];
    }
}
