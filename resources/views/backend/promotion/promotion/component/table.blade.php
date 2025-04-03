<table class="table table-bordered table-hover" style="background-color:white !important">
    <thead>
        <tr>
            <th >
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th style="width:400px">Tên chương trình</th>
            <th>Thông tin</th>
            <th >Loại khuyến mại</th>
            <th >Thời gian</th>
           
            <th >Trạng thái</th>
            <th >Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($promotions) && is_object($promotions))
            @foreach($promotions as $key => $promotion)
           
            @php
            $startDate = formatDate($promotion->startDate);
            $endDate = (is_null($promotion->endDate)) ?  'Không giới hạn' : formatDate($promotion->endDate);
            
            $status = (isset($promotion->endDate) && $promotion->endDate < now()) ? 1 : 2;
           
        @endphp
        
               <tr>
                <td>
                    <input type="checkbox" value="{{ $promotion->id }}" class="input-checkbox checkboxItem">
                </td>
                
                <td>{{ $promotion->name }}
                    <span class="{{ ($status == '1') ? 'text-danger' : 'text-success'  }} ps-2">
                        <i class="fa fa-circle"></i> {{ ($status == '1') ? 'Hết hạn' : 'Hoạt động' }}
                    </span>
                    <div class="mt-1">
                        <span class="badge bg-warning">{{ $promotion->code }}</span>
                    </div>
                </td>

                <td>
                    
                    <a href="{{ route('promotion.edit', $promotion->id) }}" class="mainColor">Xem chi tiết</a>
                </td>
            
                <td>{{ __('module.promotion')[$promotion->method] }}</td>
            
                <td>
                    {{ $startDate }} -
                     {{ $endDate ?? 'Không giới hạn' }}
                     
                     
                    
                    
                </td>
            
                <td class="text-center js-switch-{{$promotion->id}}">
                    <input type="checkbox" id="status" 
                        value="{{$promotion->publish}}" 
                        class="js-switch status" 
                        data-field="publish" 
                        data-modelId="{{ $promotion->id }}" 
                        data-model="Promotion" {{ ($promotion->publish == 2) ? 'checked' : '' }}>
                </td>
            
                <td>
                    <a href="{{ route('promotion.edit', $promotion->id) }}" class="btn btn-outline-primary">
                        <i class="fa fa-edit"></i>
                    </a>
                    <button type="button" class="btn btn-outline-danger delete-button" 
                        data-id="{{ $promotion->id }}" data-name="{{ $promotion->name }}" data-model="promotion">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
            
            @endforeach
        @endif
    </tbody>
</table>

<div class="pagination-wrapper">
    {{ $promotions->links('pagination::bootstrap-5') }}
</div>

