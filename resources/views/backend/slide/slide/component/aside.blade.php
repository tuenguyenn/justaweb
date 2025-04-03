<h5 class="mb-3 mainColor">TUỲ CHỈNH SLIDE</h5>
<hr>
@php
    $selectedNavigate = old('setting.navigate', $slide->setting['navigate'] ?? 'dots');
@endphp
<div class="row  g-3 mb-4">
    <div class="form-floating setting-item  col-lg-6">
        <input 
            type="text" 
            class="form-control" 
            id="name" 
            name="name" 
            value="{{ old('name', $slide->name ?? '') }}" 
            placeholder="Tên Slide"
        >
        <label for="name">Tên Slide</label>
    </div>
    <div class="form-floating setting-item  col-lg-6">
        <input 
            type="text" 
            class="form-control" 
            id="keyword" 
            name="keyword" 
            value="{{ old('keyword', $slide->keyword ?? '') }}" 
            placeholder="Từ khoá"
        >
        <label for="keyword">Từ khoá</label>
    </div>
</div>

<!-- Kích thước -->
<h6 class="mb-2">Kích thước <span class="text-primary">(px)</span></h6>
<div class="row g-3 mb-4">
    <div class="form-floating setting-item  col-lg-6">
        <input 
            type="number" 
            class="form-control " 
            name="setting[width]" 
            placeholder=""
            value="{{old('setting.width',($slide->setting['width'] ?? 0)) }}"
        >
        <label for="width">Chiều rộng</label>
    </div>
    <div class="form-floating setting-item  col-lg-6">
        <input 
            type="number" 
            class="form-control" 
            value="{{old('setting.height',($slide->setting['height'] ?? 0)) }}"
            name="setting[height]" 
            placeholder=""
        >
        <label for="height">Chiều cao</label>
    </div>
</div>

<!-- Hiệu ứng -->
<div class="mb-3 setting-item ">
    <label for="effect" class="form-label">Hiệu ứng</label>
    <select  name="setting[animation]" class="form-select">
        @foreach (__('module.effect') as $key => $val)
        <option value="{{$key}}"  {{ (old('setting.animation',($slide->setting['animation'] ?? null)) == $key) ? 'selected' : '' }}>{{$val}}</option>
        

        @endforeach
    </select>
</div>

<div class="mb-4 setting-item ">
    <label for="nav-arrow" class="form-label">Mũi Tên</label>
    <div class="form-check">
        <input 
        type="checkbox" 
        class="form-check-input" 
        value="accept" 
        id="nav-arrow" 
        name="setting[arrow]" 
        @if (old('setting.arrow', $slide->setting['arrow'] ?? null) === 'accept') 
        checked="checked" 
    @endif
     >
    
            <label class="form-check-label" for="nav-arrow">Ẩn thanh điều hướng</label>
    </div>
</div>

<div class="mb-4">
    <label class="form-label">Thanh Điều Hướng</label>
    <div class="setting-value">
        @foreach (__('module.navigate') as $key => $val)

        <div class="form-check">
            <input 
            type="radio" 
            class="form-check-input"
            name="setting[navigate]" 
            value="{{$key}}"
            id="navigate_{{$key}}"
            {{ $selectedNavigate === $key ? 'checked' : '' }}>
        
            <label class="navigate_{{$key}}">{{$val}}</label>
        </div>
        @endforeach
    </div>
</div>
<div class="mt-2">
    <div class="d-flex align-items-center justify-content-between">
        <h5 class=" mainColor">NÂNG CAO</h5>
        <a 
            class="text-dark toggle-collapse" 
            data-bs-toggle="collapse" 
            href="#collapseExample" 
            role="button" 
            aria-expanded="{{ old('setting.autoplay') || old('setting.hover') || old('setting.animationDelay') || old('setting.animationSpeed') ? 'true' : 'false' }}"
            aria-controls="collapseExample">
            <i class="fa fa-chevron-down"></i>
        </a>
    </div>
    <div class="ibox-content"></div>
    
    <div 
        class="collapse {{ old('setting.autoplay') || old('setting.hover') || old('setting.animationDelay') || old('setting.animationSpeed') ? 'show' : '' }}" 
        id="collapseExample">
        <div>
            <div>
                <!-- Tùy chọn 1 -->
                <div class="mb-2 form-check">
                    <input 
                        type="checkbox" 
                        class="form-check-input" 
                        id="auto-run" 
                        name="setting[autoplay]"
                        {{ old('setting.autoplay',($slide->setting['autoplay'] ?? null)) ? 'checked' : '' }}>
                    <label class="form-check-label" for="auto-run">Tự động chạy</label>
                </div>

                <!-- Tùy chọn 2 -->
                <div class="mb-2 form-check">
                    <input 
                        type="checkbox" 
                        class="form-check-input" 
                        id="pause-on-hover" 
                        name="setting[hover]"
                        {{ old('setting.hover',($slide->setting['hover'] ?? null)) ? 'checked' : '' }}>
                    <label class="form-check-label" for="pause-on-hover">Dừng khi di chuột</label>
                </div>

                <!-- Chuyển ảnh -->
                <div class="mb-2 ms-2">
                    <label for="transition-time" class="form-label">Chuyển ảnh <span class="text-primary">(ms)</span></label>
                    <input 
                        type="number" 
                        class="form-control" 
                        id="transition-time" 
                        name="setting[animationDelay]" 
                        placeholder="Nhập thời gian chuyển ảnh"
                        value="{{ old('setting.animationDelay',($slide->setting['animationDelay'] ?? null)) }}">
                </div>

                <!-- Tốc độ hiệu ứng -->
                <div class="mb-3 ms-2">
                    <label for="effect-speed" class="form-label">Tốc độ hiệu ứng</label>
                    <input 
                        type="number" 
                        class="form-control" 
                        id="effect-speed" 
                        name="setting[animationSpeed]" 
                        placeholder="Nhập tốc độ hiệu ứng"
                        value="{{ old('setting.animationSpeed',($slide->setting['animationSpeed'] ?? null)) }}">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-2 ">
    <h5 class="mainColor">SHORT CODE</h5>

<div class="ibox-content">
    <textarea 
    name="short_code" 
    class="textarea form-control">{{ old('short_code',($slide->short_code ?? null)) }}</textarea>
</div>
</div>
<script>
    $(document).on('click', '.toggle-collapse', function () {
    const $icon = $(this).find('i');
    const isExpanded = $(this).attr('aria-expanded') === 'true';

    if (isExpanded) {
        $icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
    } else {
        $icon.removeClass('fa-chevron-uo').addClass('fa-chevron-down');
    }
});

</script>
