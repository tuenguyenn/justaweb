
<head>
    <base href="{{config(key: 'app.url')}}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="robots" content="index,follow" />
    <meta name="author" content="" />
    <meta name="copyright" content="" />
    <link rel="icon" href="" type="image/png" sizes="30x30">
    <meta name= "csrf-token" content="{{csrf_token()}}">

    <title>{{$seo['meta_title']}}</title>
    <meta name="description" content="{{$seo['meta_description']}}" />
    <meta name="keywords" content="{{$seo['meta_keywords']}}" />
    <link rel="canonical" href="{{ request()->get('page', 1) == 1 ? $seo['canonical'] : request()->fullUrlWithQuery(['page' => request()->get('page')]) }}" />
    <meta property="og:locale" content="vi_VN"/>

    <meta property="og:title" content="{{$seo['meta_title']}}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="{{$seo['canonical']}}"/>
    <meta property="og:image" content="{{$seo['meta_image']}}"/>
    <meta property="og:description" content="{{$seo['meta_description']}}"/>

    @php
        $coreCss = [
            'frontend/resources/fonts/font-awesome-4.7.0/css/font-awesome.min.css',
            'frontend/resources/uikit/css/uikit.modify.css',
            'frontend/resources/plugins/wow/css/libs/animate.css',
            'frontend/resources/style.css',
            'frontend/resources/library/css/library.css',
            'frontend/css/custom.css',
            'frontend/core/plugins/jquery-nice-select-1.1.0/css/nice-select.css'


        ]
      
    @endphp
    @foreach ($coreCss as $key => $value) 
        <link rel="stylesheet" href="{{asset($value)}}">
    @endforeach
    @if(@isset($config['css']) && is_array($config['css']))
        @foreach ($config['css'] as $key => $val)
            <link href="{{asset($val)}}"  rel="stylesheet">
        @endforeach    
    @endif
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- jQuery và jQuery UI -->
         

        <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
        <script src="{{asset('frontend/resources/library/js/jquery.js')}}"></script>
       
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/uikit@3.15.24/dist/js/uikit.min.js"></script>
        <script>
             var BASE_URL ='{{ config('app.url') }}'
        </script>
</head>

    
