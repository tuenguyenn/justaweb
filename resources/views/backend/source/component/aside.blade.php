<h5 class="mb-3 mainColor">TUỲ CHỈNH NGUỒN KHÁCH</h5>
<hr>

<div class=" g-3 mb-4 pt-3" >
    <div class="form-floating setting-item mb-4 col-lg-12">
        <input 
            type="text" 
            class="form-control" 
            id="name" 
            name="name" 
            value="{{ old('name', $source->name ?? '') }}" 
            placeholder=""
        >
        <label for="name" class="ps-4">Tên Nguồn Khách</label>
    </div>
    <div class="form-floating setting-item  col-lg-12">
        <input 
            type="text" 
            class="form-control" 
            id="keyword" 
            name="keyword" 
            value="{{ old('keyword', $source->keyword ?? '') }}" 
            placeholder="Từ khoá"
        >
        <label for="keyword" class="ps-4">Từ khoá</label>
    </div>
</div>





