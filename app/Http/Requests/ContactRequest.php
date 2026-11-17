<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    /** Send people back to the form, not the top of the page. */
    protected $redirect = '/#contact';

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name'    => ['required', 'string', 'min:2', 'max:80'],
            'email'   => ['required', 'email:rfc', 'max:255'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
            // The honeypot: real people never see this field, bots fill it in.
            'website' => ['nullable', 'string'],
        ];
    }
}
