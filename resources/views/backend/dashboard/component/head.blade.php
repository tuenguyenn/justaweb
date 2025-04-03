<base href="{{config(key: 'app.url')}}">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name= "csrf-token" content="{{csrf_token()}}">
<title>Admin</title>

<link href="backend/css/animate.css" rel="stylesheet">
<link href="backend/css/style.css" rel="stylesheet">
<link href="backend/css/custom.css" rel="stylesheet">
<link href="backend/css/bootstrap.min.css" rel="stylesheet">
<link href="backend/font-awesome/css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

@if(@isset($config['css']) && is_array($config['css']))
    @foreach ($config['css'] as $key => $val)
         <link href="{{asset($val)}}"  rel="stylesheet">
    @endforeach    
@endif

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@vite(['resources/sass/app.scss', 'resources/js/app.js'])

<!-- Select2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href=" {{asset('frontend/core/plugins/jquery-nice-select-1.1.0/css/nice-select.css')}}">

<script>
    var BASE_URL ='{{ config('app.url') }}'
    var SUFFIX ='{{config('apps.general.suffix')}}'
</script>
