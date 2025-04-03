


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
                                <form method="POST" class="m-t" role="form" action="{{ route('password.new',['token'=>$token ,'email'=>$email])}}">
                                    @csrf

                                    

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
                                        <input type="password" name="password_confirmation"
                                            class="form-control form-control-lg" placeholder="Xác nhận mật khẩu">
                                    </div>

                                   
                                 

                                    <button class="btn btn-outline-dark btn-lg " type="submit">Đổi mật khẩu</button>

                                    
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