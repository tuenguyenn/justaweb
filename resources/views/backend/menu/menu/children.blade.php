
@include('backend.dashboard.component.breadcumb', [
    'title' =>   $config['seo']['create']['children'].$menu->languages->first()->pivot->name 
])
@php
$url = match($config['method']) {
    'create' => route('menu.store'),
    'update' => route('menu.update', $menu->id),
    'children' => route('menu.save.children', $menu->id), 
    default => '#', 
}
@endphp

    @include('backend.dashboard.component.formerror')


    <form action="{{ $url }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="h-100 " >
            @include('backend.menu.menu.component.list')
    
            <button type="submit" class="btn btn-primary float-right mt-2">Lưu</button>
        </div>

    </form>

   
    
    



