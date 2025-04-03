@extends('frontend.homepage.layout')
@section('content')
<div class="product-catalogue page-wrapper">
  <div class="uk-container uk-container-center">
   @include('frontend.component.breadcumb',['model'=> $productCatalogue , 'breadcumb'=> $breadcumb ?? null])
    <div class="panel-body mt10">
      @include('frontend.product.catalogue.component.filter')
      @include('frontend.product.catalogue.component.filterContent')

      @if ($products->isNotEmpty())
    
          
      <div class="product-list">
        <div class="uk-grid uk-grid-medium">
          @foreach ($productPromotion as $product)
          
          <div class="uk-width-1-2 uk-with-small-1-2 uk-width-medium-1-3 uk-width-large-1-5 mb20">

            @include('frontend.component.product-item',['product'=>$product])
          </div>
          @endforeach
         
        </div>
       
     
      
      </div>

      @include('frontend.component.pagination',['model'=>$products])
      @endif
    </div>
  </div>
</div>
@endsection