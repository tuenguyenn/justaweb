<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\UniqueAmountFromArray;

class UpdatePromotionRequest extends FormRequest
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
            'name' => 'required',
            'code' => 'unique :promotions,id,'. $this->id.'',
            'method' => ['required', 'not_in:none'],


           
        ];
    }
    
    public function withValidator($validator)
{
    $validator->sometimes('endDate', 'required', function ($input) {
        return $input->neverEndDate !== 'accept';
    });

    $validator->sometimes('promotion_order_amount_range.amountFrom', ['required', 'array', new UniqueAmountFromArray], function ($input) {
        return isset($input->method) && $input->method === 'order_amount_range';
    });

    $validator->after(function ($validator) {
        $input = $this->input();

        // Xử lý logic cho method = order_amount_range
        if ($input['method'] === 'order_amount_range') {
            if (!isset($input['promotion_order_amount_range']['amountType']) || 
                !isset($input['promotion_order_amount_range']['amountValue']) || 
                !isset($input['promotion_order_amount_range']['amountFrom'])) {
                return;
            }

            foreach ($input['promotion_order_amount_range']['amountType'] as $index => $type) {
                $amountValue = floatval(str_replace('.', '', $input['promotion_order_amount_range']['amountValue'][$index]));
                $amountFrom = floatval(str_replace('.', '', $input['promotion_order_amount_range']['amountFrom'][$index]));

               

                if ($type === 'percent' && $amountValue >= 100) {
                    $validator->errors()->add("promotion_order_amount_range.amountValue.$index", "Phần trăm không được vượt quá 100%.");
                }
            }
        }

        if ($input['method'] === 'product_and_quantity') {
           
            
            $quantity = intval($input['product_and_quantity']['quantity']);
            $maxDiscountValue = floatval(str_replace('.', '', $input['product_and_quantity']['maxDiscountValue']));
            $discountValue = floatval(str_replace('.', '', $input['product_and_quantity']['discountValue']));
            $discountType = $input['product_and_quantity']['discountType'];
            if (!isset($input['object']['id']) || empty($input['object']['id'])) {
                $validator->errors()->add('object.id', 'Vui lòng chọn ít nhất 1 sản phẩm.');
            }
            if ($quantity <= 0) {
                $validator->errors()->add("product_and_quantity.quantity", "Số lượng tối thiểu phải lớn hơn 0.");
            }

            if ($discountType === 'cash' && $discountValue > $maxDiscountValue) {
                $validator->errors()->add("product_and_quantity.discountValue", "Giá trị giảm không được vượt quá giới hạn khuyến mãi là " . number_format($maxDiscountValue) . " VND.");
            }

            if ($discountType === 'percent') {
                if ($discountValue >= 100) {
                    $validator->errors()->add("product_and_quantity.discountValue", "Phần trăm chiết khấu không được vượt quá 100%.");
                }

               
            }
        }
    });
}

    
    
    
    
    public function messages(): array
    {
        return [
            'name.required' => 'Bạn chưa nhập tên chương trình',
            'endDate.required' => 'Bạn chưa nhập ngày kết thúc',
            'method.not_in' => 'Chọn hình thức giảm giá cho chương trình.',
          
        ];
    }
    
    
    
}
