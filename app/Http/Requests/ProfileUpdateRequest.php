<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['string', 'max:255'],
            'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'address' => ['nullable', 'string', 'max:500'], // Thêm
            'phone_number' => ['nullable', 'string', 'max:20', 'regex:/^[0-9]{10,15}$/'], // Thêm
            'tax_id_number' => ['nullable', 'string', 'max:20', Rule::unique(User::class)->ignore($this->user()->id)], // Thêm
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
            'phone_number.regex' => 'Số điện thoại không hợp lệ.',
            'tax_id_number.unique' => 'Mã số thuế cá nhân đã được sử dụng.',
        ];
    }
}