<form action="{{route('review.index')}}" method="get">
    <div class="filter-section">
                 
        <input 
                type="text" 
                name="keyword" 
                id="keyword" 
                value="{{ request('keyword', old('keyword')) }}" 
                placeholder="{{__('messages.searchInput')}}" 
                class="form-control"
        >
        <button class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 4.9l-7.5 7.5"></path>
                <circle cx="11" cy="11" r="8"></circle>
            </svg>
           Tìm kiếm
        </button>
    </div>
</form>