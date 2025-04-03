<form action="{{ route($model . '.index') }}" method="get">
    <div class="filter p-3  rounded  bg-white">
        <div class="row g-3 align-items-center">
            <!-- Per Page -->
            <div class="col-md-2">
                <label for="perpage" class="form-label">{{__('messages.perpage')}}</label>
                <select name="perpage" id="perpage" class="form-select form-select-sm">
                    @for($i = 20; $i <= 200; $i += 20)
                        <option value="{{ $i }}" {{ old('perpage', request()->query('perpage', 20)) == $i ? 'selected' : '' }}>
                            {{ $i }} {{__('messages.perpage')}}
                        </option>
                    @endfor
                </select>
            </div>

          
           @if ($model =='post')
           <?php
                
                $postCatalogueId =request('post_catalogue_id') ?: old('post_catalogue_id');
            ?>
                <div class="col-md-2">
                    <label for="publish" class="form-label">{{__('messages.postCatalogue.select')}}</label>
                <select name="post_catalogue_id"  class="form-select filter-select w-auto">
                @foreach($dropdown as $key =>$val){
                        <option {{($postCatalogueId ==$key) ? 'selected':'' }}
                        value = "{{ $key }}">{{ $val }}
                    </option>
                }
           @endforeach

        </select>
    </div>
           @endif
            <!-- Publish -->
            <div class="col-md-2">
                <label for="publish" class="form-label">{{__('messages.selectStatus')}}</label>
                <select name="publish" id="publish" class="form-select">
                    @foreach (__('messages.publish') as $option => $val)
                        <option value="{{ $option }}" {{ request('publish', old('publish')) == $option ? 'selected' : '' }}>
                            {{ $val }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- User Group -->
            @if ($model == 'user')
            <div class="col-md-2">
                <label for="user_catalogue_id" class="form-label">{{__('messages.userCatalogue.selectRole')}}</label>
                <select name="user_catalogue_id" id="user_catalogue_id" class="form-select">
                    @foreach (__('messages.roles') as $option => $val)
                    <option value="{{ $option }}" {{ request('roles', old('roles')) == $option ? 'selected' : '' }}>
                        {{ $val }}
                    </option>
                @endforeach
                </select>
            </div>
            @endif

             @if ($model =='user.catalogue')
                <div class="col-md-2 text-end">
                    <label class="form-label d-block">&nbsp;</label>
                <a href="{{ route($model .'.permission') }}" class="btn btn-warning ">
                    <i class="fa fa-key"></i> {{__('messages.userCatalogue.permission.title')}}
                </a>
            </div>
            @endif
            <!-- Keyword Search -->
            <div class="col-md-4">
                <label for="keyword" class="form-label">{{__('messages.keyword')}}</label>
                <div class="input-group">
                    <input 
                        type="text" 
                        name="keyword" 
                        id="keyword" 
                        value="{{ request('keyword', old('keyword')) }}" 
                        placeholder="{{__('messages.searchInput')}}" 
                        class="form-control"
                    >
                    <button type="submit" name="search" value="search" class="btn btn-primary">
                        <i class="fa fa-search"></i> {{__('messages.search')}}
                    </button>
                </div>
            </div>

           
              <!-- Action Buttons -->
              @if ($model !='order')
              <div class="col-md-2 text-end  ms-auto">
                <label class="form-label d-block">&nbsp;</label>
                <a href="{{ route($model . '.create') }}" class="text-decoration-none">
                    <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor" aria-hidden="true" class="xfx01vb x1lliihq x1tzjh5l x1k90msu x2h7rmj x1qfuztq" style="--color:var(--accent)"><path d="M18 11h-5V6a1 1 0 0 0-2 0v5H6a1 1 0 0 0 0 2h5v5a1 1 0 0 0 2 0v-5h5a1 1 0 0 0 0-2z"></path></svg> 
                    {{__('messages.new')}}
                </a>
            </div>
            @endif
        </div>
    </div>
</form>
