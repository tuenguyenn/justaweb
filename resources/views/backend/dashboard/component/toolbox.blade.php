<div class="ibox-tools d-flex align-items-center gap-3 d-none">
  
    <!-- Nút bật/tắt trạng thái -->
    <div class="d-flex align-items-center gap-2">
        <button class="btn btn-sm btn-success changeAllStatus" 
                data-field="publish" data-model="{{ $model }}" data-value="2">
            <i class="fa fa-check-circle me-1"></i> {{ __('messages.active') }}
        </button>
        <button class="btn btn-sm btn-danger changeAllStatus" 
                data-field="publish" data-model="{{ $model }}" data-value="1">
            <i class="fa fa-times-circle me-1"></i> {{ __('messages.unactive') }}
        </button>
    </div>

  
</div>
