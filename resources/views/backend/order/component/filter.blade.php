<form action="{{ route('order.index') }}" method="get">
    <div class="filter   rounded  bg-white">
        <div class="row g-3 align-items-center">
            <!-- Per Page -->
            <div class="col-md-1">
                <label for="perpage" class="form-label">{{__('messages.perpage')}}</label>
                <select name="perpage" id="perpage" class="niceSelect ">
                    @for($i = 20; $i <= 200; $i += 20)
                        <option value="{{ $i }}" {{ old('perpage', request()->query('perpage', 20)) == $i ? 'selected' : '' }}>
                            {{ $i }} {{__('messages.perpage')}}
                        </option>
                    @endfor
                </select>
            </div>

         
            <div class="col-md-1 d-flex flex-column">
                <label for="confirm" class="form-label">{{ __('messages.selectStatus') }}</label>
                <select name="confirm" id="confirm" class="niceSelect">
                    @foreach (__('frontend.confirm') as $option => $val)
                        <option value="{{ $option }}" {{ request('confirm', old('confirm')) == $option ? 'selected' : '' }}>
                            {{ $val }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-2 ">
                <label for="payment" class="form-label">Thanh toán :</label>
                <select name="payment" id="payment" class="niceSelect wide">
                    @foreach (__('frontend.payment') as $option => $val)
                        <option value="{{ $option }}" {{ request('payment', old('payment')) == $option ? 'selected' : '' }}>
                            {{ $val }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="delivery" class="form-label">Giao hàng :</label>
                <select name="delivery" id="delivery" class="niceSelect wide">
                    @foreach (__('frontend.delivery') as $option => $val)
                        <option value="{{ $option }}" {{ request('delivery', old('delivery')) == $option ? 'selected' : '' }}>
                            {{ $val }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="method" class="form-label">Phương thức</label>
                <select name="method" id="method" class="niceSelect wide">
                    <option value="none">Chọn phương thức</option>
                    @foreach (__('payment.method') as $option => $val)
                        <option value="{{ $val['name'] }}" {{ request('method', old('method')) == $val['name'] ? 'selected' : '' }}>
                            {{ $val['title']}}
                        </option>
                    @endforeach
                </select>
            </div>
           
            <div class="col-md-2">
                <label for="keyword" class="form-label">Thời gian</label>
                <div class="input-group">
                    <div class="date-item-box">
                        <input type="text" name="created_at" value="{{ request('created_at', old('created_at')) }}" class="rangepicker form-control">
                   </div>
                    
                </div>
            </div>
            
            <!-- Keyword Search -->
            <div class="col-md-2">
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
         
        </div>
    </div>
</form>
