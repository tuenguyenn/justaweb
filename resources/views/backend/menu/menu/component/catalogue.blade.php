<div class=" mt-4 me-3">
    <div class="d-flex">
        <div class="col-lg-4 p-3 me-3 ">
            <h3 class="mb-3">Vị trí Menu</h3>
            <p>Vị trí hiển thị riêng cho từng Website.</p>
            <p>Lựa chọn vị trí cần hiển thị.</p>
        </div>

        <div class="col-lg-8 p-4 bg-white border rounded me-2">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5">Chọn vị trí hiển thị <span class="text-danger">(*)</span></h2>
                <button type="button" class="btn btn-danger createMenuCatalogue">
                    Tạo vị trí <i class="fa fa-plus"></i>
                </button>
            </div>

           <div class="d-flex">
            <div class="mb-3 col-lg-6">
                @if (count($menuCatalogues))
                    <label for="menu_catalogue_id" class="form-label">Vị trí hiển thị</label>
                    <select id="menu_catalogue_id" name="menu_catalogue_id" class="form-select">
                        <option  value=""> [Chọn vị trí hiển thị]</option>
                        
                        @foreach ($menuCatalogues as $key =>$val)
                        <option {{(isset($menuCatalogue) && $menuCatalogue->id == $val->id) ? 'selected' :''}} value="{{ $val->id }}">{{ $val->name }}</option>
                        @endforeach
                        
                    </select>
                @endif
                
            </div>
          
           </div>
        </div>
    </div>
</div>
