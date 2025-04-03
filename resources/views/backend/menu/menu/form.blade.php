
@include('backend.dashboard.component.breadcumb', [
    'title' => ($config['method'] == 'edit' ? $config['seo']['update']['title'] : $config['seo']['create']['title'])
])


    @include('backend.dashboard.component.formerror')


    <form action="{{  route('menu.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="h-100 " >
            @include('backend.menu.menu.component.catalogue')

            @include('backend.menu.menu.component.list')
            <input type="hidden" name="redirect" value="{{$id ?? 0}}">
            <button type="submit" class="btn btn-primary float-right mt-2">Lưu</button>
        </div>

    </form>
    @include('backend.menu.menu.component.popup')

   
    
    



