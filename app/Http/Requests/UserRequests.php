<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('gerenciar-usuarios');
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $usuario = $this->route('usuario');

        return [
            'name'  => ['required', 'string', 'min:3', 'max:100'],

            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($usuario)],

            'perfil' => ['required', Rule::in(['admin', 'funcionario'])],

            'password' => [
                $usuario ? 'nullable' : 'required',
                'string',
                'min:6',
                'confirmed',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'Informe o nome.',
            'name.min'           => 'O nome deve ter pelo menos 3 letras.',
            'name.max'           => 'O nome pode ter no máximo 100 caracteres.',
            'email.required'     => 'Informe o e-mail.',
            'email.email'        => 'Digite um e-mail válido.',
            'email.unique'       => 'Já existe um usuário com este e-mail.',
            'perfil.required'    => 'Escolha o perfil.',
            'perfil.in'          => 'Perfil inválido.',
            'password.required'  => 'Informe a senha.',
            'password.min'       => 'A senha deve ter pelo menos 6 caracteres.',
            'password.confirmed' => 'A confirmação da senha não confere.',
        ];
    }
}