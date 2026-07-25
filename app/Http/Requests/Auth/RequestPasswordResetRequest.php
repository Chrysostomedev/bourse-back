<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RequestPasswordResetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // On ne révèle pas si l'email existe ou non dans le message
            // d'erreur (voir PasswordResetController) — mais on le
            // valide quand même ici pour éviter un crash plus loin.
            'email' => ['required', 'email'],
        ];
    }
}