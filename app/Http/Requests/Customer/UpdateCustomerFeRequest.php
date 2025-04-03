<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerFeRequest extends FormRequest
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
            'email' => 'required|string|email|unique:customers,email,'.$this->id.'|max:255',
            'fullname' => 'required|string|max:255',
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

            'fullname.required' => 'Bạn chưa nhập tên',
           
         
        ];
    }
}
