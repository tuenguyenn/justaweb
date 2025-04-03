<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLanguageRequest extends FormRequest
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
    return [
        'name' => 'required|string',
        'canonical'=> 'required|unique:languages',
        
        
        
    ];
}

    public function messages(): array
    {
        return [
            'name.required' => 'Bạn chưa nhập tên ngôn ngữ',
            'canonical.required' => 'Bạn chưa nhập từ khoá ngôn ngữ',
            'canonical.unique' => 'Đã tồn tại từ khoá',


            
        ];
    }
}
