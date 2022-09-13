<?php

namespace App\Http\Requests\Request;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules(): array
  {
    return [
      'title' => 'required|unique:posts|max:255',
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
      'title.required' => 'Title is required',
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
