<form action="{{route('permission.index')}}" method="get">
    <div class="filter">
        <div class="perpage mb-3">
            <select name="perpage" class="form-select form-select-sm filter-select">
                @for($i = 20; $i <= 200; $i += 20)
                    <option value="{{ $i }}" {{ old('perpage', request()->query('perpage', 20)) == $i ? 'selected' : '' }}>
                        {{ $i }} {{ __('messages.perpage') }}  
                    </option>
                @endfor
            </select>
        </div>
        <div class="action">
            <div class="d-flex align-items-center gap-3">
               
              
    
                <div class="filter-search">
                    <div class="input-group">
                        <input 
                            type="text" 
                            name="keyword" 
                            value="{{request('keyword') ?: old('keyword')}}" 
                            placeholder="{{ __('messages.searchInput') }}  " 
                            class="form-control filter-input"
                        >
                        <button type="submit" name="search" value="search" class="btn btn-primary search-btn">
                            {{ __('messages.search') }}  
                        </button>
                    </div>
                </div>
    
                <a href="{{ route('permission.create') }}" class="btn btn-danger">
                    <i class="fa fa-plus"></i> {{ __('messages.permission.create.title') }}
                </a>
            </div>
        </div>
    </div>
    
    
</form>