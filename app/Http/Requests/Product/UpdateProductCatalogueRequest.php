<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductCatalogueRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'canonical'=> 'required|unique:routers,canonical,' . $this->id.',module_id',

            
            
        ];
    }
    
        public function messages(): array
        {
            return [
                'name.required' => 'Bạn chưa nhập tên tiêu đề ',
                'canonical.required' => 'Bạn chưa nhập đường dẫn',
                'canonical.unique' => 'Đã tồn tại đường đẫn',
    
    
                
            ];
        }
}
