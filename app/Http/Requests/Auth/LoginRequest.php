<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|max:255',
            'password' => 'required',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Email is required',
            'password.required' => 'Password is required',
        ];
    }

    /**
     * @param array $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function response(array $errors): \Illuminate\Http\JsonResponse
    {
        // Always return JSON.
        return response()->json($errors, 422);
    }
}
