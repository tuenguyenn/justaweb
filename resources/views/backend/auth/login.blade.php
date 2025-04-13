<!DOCTYPE html>
<html>

<head>


      <link href="backend/font-awesome/css/font-awesome.css" rel="stylesheet">
  
  

      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>


  
</head>
</html>

<section class="vh-100 gradient-custom">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
          <div class="card bg-white text-dark" style="border-radius: 1rem;">
            <div class="card-body p-5 text-center">
  
              <div class="mb-md-5 mt-md-4 pb-5">
  
                <h2 class="fw-bold mb-2 text-uppercase">ADMIN</h2>
                <p class="text-dark-50 mb-5">Nhập tài khoản và mật khẩu</p>
                <form method="POST" class="m-t" role="form" action={{ route('auth.login')}}>
                    @csrf
                    <div data-mdb-input-init class="form-outline form-white mb-4">
                      <input type="text" 
                          name="email"
                          class="form-control form-control-lg"
                          placeholder="Username"
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
                        placeholder="Password" >
                        @if ($errors->has('password'))
                        <span class="error-message text-start text-danger d-block mt-1">*
                            {{$errors->first('password')}}    
                        </span>
                    @endif
                    </div>
  
                <p class="small mb-5 pb-lg-2"><a class="text-dark-50" href="#!">Quên mật khẩu?</a></p>
  
                <button data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-dark btn-lg px-5" type="submit">Đăng nhập</button>
                <i class="bi bi-arrow-down-left-square"></i>
                <div class="d-flex justify-content-center text-center mt-4 pt-1">
                  <a href="#!" class="text-dark"><i class="fs4 fa fa-facebook fa-lg"></i></a>
                  <a href="#!" class="text-dark"><i class="fs4 fa fa-twitter fa-lg mx-4 px-2"></i></a>
                  <a href="#!" class="text-dark"><i class="fs5 fa fa-google fa-lg"></i></a>
                </div>
  
              </div>
  
              <div>
                <p class="mb-0">Chưa có tài khoản? <a href="#!" class="text-dark-50 fw-bold">Đăng ký ngay</a>
                </p>
              </div>
  
            </div>
          </div>
        </div>
      </div>
    </div>
    <style>
        .gradient-custom {
/* fallback for old browsers */
background: #6a11cb;

/* Chrome 10-25, Safari 5.1-6 */
background: -webkit-linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1));

/* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
background: linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1))
}
    </style>
  </section>
