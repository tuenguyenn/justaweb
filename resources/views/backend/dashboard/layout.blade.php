<!DOCTYPE html>
<html>
<head>

    @include('backend.dashboard.component.head')
</head>
<body id="body-pd" style=" background-color: #F2F9FF">

    @include('backend.dashboard.component.nav')
    @include('backend.dashboard.component.sidebar')

        <div class="container-fluid" style="margin-top: 80px ;" >
                    @include($template)
                   
        </div>
        {{-- @include('backend.dashboard.component.footer') --}}    

    @include('backend.dashboard.component.script')

</body>
</html>
