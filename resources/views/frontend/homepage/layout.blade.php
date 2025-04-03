<!DOCTYPE html>
<html lang="en">
    @include('frontend.component.head')
    <style>
        .swiper-button-next{
        right:0;
        left:initial;
        top:-30px;
        background-image: url('{{ asset('frontend/resources/img/arrow.png') }}');
        background-size:50%;
        background-color: #f2f3f4;
        width:40px;
        height:40px;
        border-radius: 50%;
        transition: all 0.5s ease;
    }
    .swiper-button-prev{
        right:50px;
        left:initial;
        top:-30px;
        background-image: url('{{ asset('frontend/resources/img/arrow.png') }}');
        background-size:50%;
        background-color: #f2f3f4;
        width:40px;
        height:40px;
        border-radius: 50%;
        transform: rotate(180deg);
    
    }
    </style>
<body>
  
    @include('frontend.component.header')
    @yield('content')
    @include('frontend.component.footer')
    @include('frontend.component.script')
    @include('frontend.chat.chatbot')
    {{-- @include('frontend.component.popup') --}}

</body>
</html>