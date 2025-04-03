<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
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
        return [
            'email' => 'required|string|email|unique:users,email,'.$this->id.'|max:255',
            'name' => 'required|string|max:255',
            'user_catalogue_id' => 'required|integer|gt:0',
        ];
        }

    public function messages(): array
    {
        return [
            'email.required' => 'Bạn chưa nhập email',
            'email.email' => 'Email không đúng định dạng. Ví dụ abc@gmail.com',
            'email.unique' => 'Email đã được sử dụng',
            'email.max' => 'Độ dài tối đa là 255 kí tự',
            'email.string' => 'Sai định dạng gmail',

            'name.required' => 'Bạn chưa nhập tên',
           
            'user_catalogue_id.required' => 'Vui lòng chọn danh mục người dùng',
            'user_catalogue_id.gt' => 'Vui lòng chọn danh mục người dùng ',
        ];
    }
}
