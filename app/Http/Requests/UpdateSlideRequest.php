<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSlideRequest extends FormRequest
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
            'keyword' => 'required| unique:slides,keyword,'.$this->id,
            'slide.image' => 'required',
            
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên Slide',
            'keyword.required' => 'Vui lòng nhập tên từ khoá cho Slide',
            'keyword.unique' => 'Từ khoá đã tồn tại',
            'slide.image.required' => 'Vui lòng chọn ảnh cho Slide',

        ];
    }
}
