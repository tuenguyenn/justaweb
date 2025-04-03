@extends('frontend.homepage.layout')
@section('content')


<div class="d-flex">
    <div class="col-lg-6">
    </div>
    <div class="col-lg-6">
        <div class=" ">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-white text-dark" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">

                            <div class="mb-md-5 ">

                                <h2 class="fw-bold mb-2 text-uppercase mb-4">Đăng Ký</h2>
                                <form method="POST" class="m-t" role="form" action="{{ route('customerfe.store')}}">
                                    @csrf

                                    <div class="form-outline form-white mb-4">
                                        <input type="text" name="name" class="form-control form-control-lg"
                                            placeholder="Họ và tên" value="{{ old('name') }}">
                                        @if ($errors->has('name'))
                                        <span class="error-message text-start text-danger d-block mt-1">*
                                            {{ $errors->first('name') }}
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-outline form-white mb-4">
                                        <input type="email" name="email" class="form-control form-control-lg"
                                            placeholder="Email" value="{{ old('email') }}">
                                        @if ($errors->has('email'))
                                        <span class="error-message text-start text-danger d-block mt-1">*
                                            {{ $errors->first('email') }}
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-outline form-white mb-4">
                                        <input type="password" name="password" class="form-control form-control-lg"
                                            placeholder="Mật khẩu">
                                        @if ($errors->has('password'))
                                        <span class="error-message text-start text-danger d-block mt-1">*
                                            {{ $errors->first('password') }}
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-outline form-white mb-3">
                                        <input type="password" name="re_password"
                                            class="form-control form-control-lg" placeholder="Xác nhận mật khẩu">
                                    </div>

                                    <button class="btn btn-outline-dark btn-lg " type="submit">Đăng ký</button>

                                    <div class="d-flex justify-content-center text-center ">
                       
                        
                                        <a href="{{ route('google.login') }}" class="  p-3 rounded"><img src="{{asset('userfiles/sign-in-with-google.jpg')}}" alt=""></a>
                                        
                                      </div>
                                </form>

                            </div>
                            <div class="d-flex align-items-center ms-3 p-2">
                                <p class="me-2 mb-0">Đã có tài khoản?</p>
                                <form action="{{ route('customer') }}" method="GET">
                                    <button type="submit" class="btn btn-link text-dark fw-bold p-0 m-0">
                                        Đăng nhập ngay
                                    </button>
                                </form>
                            </div>
                            
                            
                          

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <style>
        .gradient-custom {

background: -webkit-linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1));

/* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
background: linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1))
}
    </style>

@endsection