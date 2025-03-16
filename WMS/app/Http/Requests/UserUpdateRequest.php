<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user');

        return [
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'username' => 'sometimes|required|string|max:255|unique:users,username,' . $userId,
            'email' => 'sometimes|required|email|max:255|unique:users,email,' . $userId,
            'phone_number' => 'nullable|string|max:20|unique:users,phone_number,' . $userId,
            'role' => 'sometimes|required|string|in:admin,supervisor,staff',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'username.unique' => 'This username is already taken. Please choose another one.',
            'email.unique' => 'This email address is already registered. Please use a different email.',
            'phone_number.unique' => 'This phone number is already registered. Please use a different number.',
        ];
    }
} 