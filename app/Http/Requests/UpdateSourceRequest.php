<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSourceRequest extends FormRequest
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
            'keyword' => 'required| unique:sources,keyword,'.$this->id,
           

            
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên Nguồn khách',
            'keyword.required' => 'Vui lòng nhập từ khoá cho Nguồn khách',
            'keyword.unique' => 'Từ khoá đã tồn tại',
            

        ];
    }
}
