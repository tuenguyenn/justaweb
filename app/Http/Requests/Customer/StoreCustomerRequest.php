<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    /**
     * Determine if the customer is authorized to make this request.
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
    return [
        'email' => 'required|string|email|unique:customers|max:255',
        'name' => 'required|string|max:255',
        'password' => 'required|string|min:6',
        're_password' => 'same:password',
        'customer_catalogue_id' => 'required|integer|gt:0',
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
            'password.required' => 'Bạn chưa nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            're_password.same' => 'Mật khẩu nhập lại không khớp',
            'customer_catalogue_id.required' => 'Vui lòng chọn danh mục người dùng',
            'customer_catalogue_id.gt' => 'Vui lòng chọn danh mục người dùng ',
        ];
    }
}
