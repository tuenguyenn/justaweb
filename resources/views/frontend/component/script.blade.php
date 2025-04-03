<script async defer crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v17.0&appId=103609027035330&autoLogAppEvents=1" nonce="E1aWx0Pa"></script>

@php
   
$coreJs = [
    'frontend/resources/plugins/wow/dist/wow.min.js',
    'frontend/resources/uikit/js/uikit.min.js',
    'frontend/resources/uikit/js/components/sticky.min.js',
    'frontend/resources/function.js',
    'frontend/resources/plugins/jquery-nice-select-1.1.0/js/jquery.nice-select.min.js',
    'frontend/core/plugins/jquery-nice-select-1.1.0/js/jquery.nice-select.js'
];
@endphp

@if(isset($config['js']) && is_array($config['js']))
   
    @foreach ($config['js'] as $val)
        <script src="{{ asset($val) }}"></script>
    @endforeach    
@endif


@foreach ($coreJs as $value)
    <script src="{{ asset($value) }}"></script>
@endforeach
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>



<script>
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right",
        timeOut: "3000"
    };
</script>