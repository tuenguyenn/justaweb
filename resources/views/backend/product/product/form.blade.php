
@include('backend.dashboard.component.breadcumb', [
    'title' => ($config['method'] == 'edit' ? $config['seo']['update']['title'] : $config['seo']['create']['title'])
])

@php
    $url = ($config['method'] == 'create' 
        ? route('product.store') 
        : route('product.update', $product->id))
@endphp


@php
    $url = ($config['method']== 'create' ? route('product.store'):route('product.update',$product->id))
@endphp

 
    @include('backend.dashboard.component.formerror')

 
    <form  action="{{$url}}" method="POST" enctype="multipart/form-data">
        @csrf
        <!-- Name -->
        <div class="d-flex ">
            
           <div class="d-flex flex-column"> 
                @include('backend.dashboard.component.content', [
                    'model' => $product ?? null,
                    
                    'offCol9' => true,
                ])
                @include('backend.product.product.component.album' )

           </div>
            
            @include('backend.product.product.component.aside')
        </div> 

        @include('backend.product.product.component.variant')

        @include('backend.dashboard.component.seo',['model'=>($product)?? null]) 

      <button type="submit" class="btn btn-primary float-right " name="send" value="send">{{__('messages.btnSave')}}</button>

      
    </form>

    



