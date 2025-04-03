<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWidgetRequest extends FormRequest
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
            
            'name' =>'required',
            'keyword' => 'required| unique:widgets',
            'short_code' => 'required| unique:widgets',


            
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên Widget',
            'keyword.required' => 'Vui lòng nhập từ khoá cho Widget',
            'keyword.unique' => 'Từ khoá đã tồn tại',
            'short_code.required' => 'Vui lòng nhập mã ngắn cho Widget',
            'short_code.unique' => 'Mã ngắn đã tồn tại',


            


            
        ];
    }
}
