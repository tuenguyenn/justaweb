
@include('backend.dashboard.component.breadcumb', [
    'title' => ($config['method'] == 'edit' ? $config['seo']['update']['title'] : $config['seo']['create']['title'])
])

@php
    $url = ($config['method'] == 'create' 
        ? route('product.catalogue.store') 
        : route('product.catalogue.update', $productCatalogue->id))
@endphp

<div>
 
    @include('backend.dashboard.component.formerror')

 
    <form  action="{{$url}}" method="POST" enctype="multipart/form-data">
        @csrf
        <!-- Name -->
        <div class="d-flex h-100">
            @include('backend.dashboard.component.content',['model'=>($productCatalogue)?? null])
            @include('backend.dashboard.component.aside',['model'=>($productCatalogue)?? null])
          </div>
        </div>
         
  
        @include('backend.dashboard.component.seo',['model'=>($productCatalogue)?? null])

      <button type="submit" class="btn btn-primary float-right " name="send" value="send">{{__('messages.btnSave')}}</button>

      
    </form>
</div>
    



