<div class="col-lg-12 bg-white ">
    <div class="p-4">
        <h5 class="mainColor">THÔNG TIN CHUNG</h5>
        <hr>
        <div class="row g-3">
          @if (!isset($offTitle))
          <div class="col-md-6">
            <label for="name">Tên chương trình (<span class="text-danger">*</span>)</label>
            <input type="text" name="name" class="form-control" value="{{old('name',($model->name) ?? '')}}" placeholder="Nhập tên chương trình">
          </div>
          @endif
          <div class="col-md-6">
            <label for="code">Mã khuyến mại {!! (isset($offTitle)) ? '<span class="text-danger">(*)</span>' : ' '!!} </label>
            <input type="text" name="code" class="form-control" value="{{old('code',($model->code) ?? '')}}" placeholder="Nếu không nhập hệ thống sẽ tự động tạo mã ">
          </div>
          <div class="col-12">
            <label for="">Mô tả khuyến mại</label>
            <textarea id="" class="form-control" name="description" placeholder="Nhập mô tả">{{old('description',($model->description) ?? '')}}</textarea>
          </div>
        </div>
    </div>
</div>


