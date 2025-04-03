@extends('frontend.homepage.layout')
@section('content')


<div class="d-flex mt-5">
    <div class="col-lg-6">

    </div>
    <div class="col-lg-6">
        <div class=" ">
            <div class="row d-flex justify-content-center align-items-center h-100">
              <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card bg-white text-dark" style="border-radius: 1rem;">
                  <div class="card-body pt-3 p-5 pb-3 text-center">
        
                    <div class="mb-md-5 pb-5">
        
                      <h2 class="fw-bold mb-2 text-uppercase  mb-4">Đăng Nhập</h2>
                      <form method="POST" class="m-t" role="form" action={{ route('customer.login')}}>
                          @csrf
                          <div data-mdb-input-init class="form-outline form-white mb-4">
                            <input type="text" 
                                name="email"
                                class="form-control form-control-lg"
                                placeholder="Email"
                                value="{{ old('email') }}">
                            @if ($errors->has('email'))
                                <span class="error-message text-start text-danger d-block mt-1">*
                                  {{$errors->first('email')}}
                                </span>
                            @endif
                        </div>
                        
                        
          
                          <div data-mdb-input-init class="form-outline form-white mb-4">
                              <input type="password" 
                              name="password"
                              class="form-control form-control-lg" 
                              placeholder="Mật khẩu" >
                              @if ($errors->has('password'))
                              <span class="error-message text-start text-danger d-block mt-1">*
                                  {{$errors->first('password')}}    
                              </span>
                          @endif
                          </div>
        
                      <p class="small mb-3 pb-lg-2"><a class="text-dark-50" href="{{route('password.request')}}">Quên mật khẩu?</a></p>
        
                      <button data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-dark btn-lg px-5" type="submit">Đăng nhập</button>
                      <i class="bi bi-arrow-down-left-square"></i>
                      <div class="d-flex justify-content-center text-center ">
                       
                        
                        <a href="{{ route('google.login') }}" class="  p-3 rounded"><img src="{{asset('userfiles/sign-in-with-google.jpg')}}" alt=""></a>
                        
                      </div>
         
        
                    <div class="mt-4">
                      <p class="mb-0">Chưa có tài khoản? <a href="{{route('customerfe.create')}}" class="text-dark-50 fw-bold">Đăng ký ngay</a>
                      </p>
                    </div>
        
                  </div>
                </div>
              </div>
            </div>
          </div>
    </div>
</div>
</div>  

@endsection