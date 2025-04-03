<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGenerateRequest extends FormRequest
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
        'name' => 'required|unique:generates',
        'schema' => 'required',
        // 'module_type.gt:0'=> 'Bạn phải chọn kiểu Module'

     
        
    ];
}

    public function messages(): array
    {
        return [
            'name.required' => 'Bạn chưa nhập tên Module',
            'name.unique'=> 'Vui lòng chọn tên Module khác',
            'schema.required'=> 'Bạn chưa nhập schema',
            

            
        ];
    }
}
