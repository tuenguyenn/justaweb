@extends('frontend.homepage.layout')
@section('content')
<div class="product-catalogue page-wrapper">
  <div class="uk-container uk-container-center">
    

    <div class="panel-head">
      @include('frontend.product.product.component.product-detail',['model'=> $product ])
     
    </div>
    @include('frontend.product.product.component.review')
 
  </div>
</div>
@endsection