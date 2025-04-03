@extends('frontend.homepage.layout')
@section('content')


<div class="cart-container">
    
    <div class="uk-container uk-container-center">
        @include('backend.dashboard.component.formerror')

        @if ($route !=  null)
            <form action="{{ route($route, ['id' => $customer->id]) }}" class="uk-form form" method="{{ isset($method) ? 'GET' : 'POST'}}">
            @endif
            @csrf
            <div class="cart-wrapper">
                <div class="uk-grid uk-grid-medium">
                    <div class="uk-width-1-6">
                        @include('frontend.customer.component.item')
                    </div>
                    
                    @include($template)

                    <!-- HTML !-->


                    
                   

                </div>
                
                </div>
                @if ($route !=  null)
        </form>
        @endif
    </div>
</div>




<!-- Modal xác nhận xóa (UIkit) -->



@endsection