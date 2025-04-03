<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class StoreTranslateRequest extends FormRequest
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
            'translate_name' => 'required|string',
           'translate_canonical' => [
            'required',
        function ($attribute, $value, $fail) {
            $option = $this->input('option');
            $controllerName = 'App\Http\Controllers\Frontend\\' . $option['model'] . 'Controller';

            // Kiểm tra xem đường dẫn canonical đã tồn tại chưa, nhưng không kiểm tra chính nó
            $exist = DB::table('routers')
                ->where('canonical', $value)
                ->where(function ($query) use ($option, $controllerName) {
                    $query->where('language_id', '<>', $option['languageId'])
                        ->orWhere('module_id', '<>', $option['id'])
                        ->orWhere('controllers', '<>', $controllerName);
                })
            
                ->exists();

        if ($exist) {
            $fail('Đường dẫn đã tồn tại. Vui lòng chọn đường dẫn khác.');
        }
    }
]

        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'translate_name.required' => 'Bạn chưa nhập tiêu đề',
            'translate_canonical.required' => 'Bạn chưa nhập đường dẫn',
        ];
    }
}
