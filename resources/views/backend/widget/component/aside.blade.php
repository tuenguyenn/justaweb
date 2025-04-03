<h5 class="mb-3 mainColor">TUỲ CHỈNH WIDGET</h5>
<hr>

<div class="row  g-3 mb-4">
    <div class="form-floating setting-item  col-lg-6">
        <input 
            type="text" 
            class="form-control" 
            id="name" 
            name="name" 
            value="{{ old('name', $widget->name ?? '') }}" 
            placeholder=""
        >
        <label for="name">Tên Widget</label>
    </div>
    <div class="form-floating setting-item  col-lg-6">
        <input 
            type="text" 
            class="form-control" 
            id="keyword" 
            name="keyword" 
            value="{{ old('keyword', $widget->keyword ?? '') }}" 
            placeholder="Từ khoá"
        >
        <label for="keyword">Từ khoá</label>
    </div>
</div>






<div class="mt-2 ">
    <h5 class="mainColor">SHORT CODE</h5>

<div class="ibox-content">
    <textarea 
    name="short_code" 
    class="textarea form-control">{{ old('short_code',($widget->short_code ?? null)) }}</textarea>
</div>
</div>
