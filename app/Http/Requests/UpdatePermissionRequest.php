<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules(): array
    {
        return [
            'canonical' => 'required|unique:permissions,canonical,' . $this->id,
            'name' => 'required|string|max:255',
        ];
        }

    public function messages(): array
    {
        return [
            'canonical.required' => 'Bạn chưa nhập đường dẫn ',
            'canonical.unique' => 'đường dẫn đã được sử dụng',

            'name.required' => 'Bạn chưa nhập tên ',
           
           
        ];
    }
}
