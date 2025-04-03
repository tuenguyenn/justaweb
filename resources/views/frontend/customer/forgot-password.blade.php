

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

                                <h2 class="fw-bold mb-2 text-uppercase mb-4">QUÊN MẬT KHẨU</h2>
                                <form method="POST" class="m-t" role="form" action="{{ route('password.email')}}">
                                    @csrf

                                    

                                    <div class="form-outline form-white mb-4">
                                        <input type="email" name="email" class="form-control form-control-lg"
                                            placeholder="Nhập email đã đăng ký" value="{{ old('email') }}">
                                        @if ($errors->has('email'))
                                        <span class="error-message text-start text-danger d-block mt-1">*
                                            {{ $errors->first('email') }}
                                        </span>
                                        @endif
                                    </div>

                                   
                                 

                                    <button class="btn btn-outline-dark btn-lg " type="submit">Đăng ký</button>

                                    
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

    

@endsection