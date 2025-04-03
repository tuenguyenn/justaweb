<div class="voucher-list">
    @if(count($validPromotions) > 0)
    @foreach ($validPromotions as $promotion)
        @php
            $amountFromList = $promotion['discountInformation']['info']['amountFrom'] ?? [];
            $amountValueList = $promotion['discountInformation']['info']['amountValue'] ?? [];
            $amountTypeList = $promotion['discountInformation']['info']['amountType'] ?? [];
            $endDate = formatDate($promotion['endDate']);
            $validAmount = $promotion['validAmounts'] ?? []; // Lấy danh sách mức hợp lệ
        @endphp

        @foreach ($amountFromList as $key => $amount)
            
                @php
                    $discountValue = $amountValueList[$key] ?? 0; // Giá trị giảm giá
                    $discountType = $amountTypeList[$key] ?? 'cash'; 
                    $valid = (in_array($amount, $validAmount));
                   
                    
                @endphp
                <div class="voucher-item {{($valid == true) ? '' : 'invalid'}}"
                    data-amount="{{ $amount }}"
                    data-promotion-discount="{{ $discountValue }}"
                    data-promotion-type="{{ $discountType }}"
                    data-promotion-name ="{{$promotion['name']}}"
                    >

                    <div class="voucher-left"></div>
                    <div class="voucher-right">
                        <div class="voucher-title">
                            {{ $promotion['name'] }} 
                        </div>
                        <div class="voucher-description">
                            <p>Giảm giá {{ number_format($discountValue) . (($discountType == 'cash') ? 'đ' : '%') }} </p>
                            <p>Đơn hàng từ {{ number_format($amount) }}</p>
                            <p>Áp dụng đến {{ $endDate }} </p>
                        </div>
                    </div>
                   
                </div>
            
        @endforeach
    @endforeach
@endif
</div>
<div class="voucher-form">
    <input type="text" placeholder="Nhập mã giảm giá" name ="voucher" readonly>
    <a href="" class="apply-voucher">Áp dụng</a>
   </div>
   <input type="hidden" name="promotionName" value="">