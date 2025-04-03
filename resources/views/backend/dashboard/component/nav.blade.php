
  <header class="header bg-white border-bottom mb-5" id="header">
      <div class="header_toggle "> 
        <i class='bx bx-menu p-2' id="header-toggle"></i> 
      </div>
      <div class="container">
          <div class="row height d-flex justify-content-center align-items-center">
            <div class="col-md-6">
              <div class="form">
                <i class="fa fa-search"></i>
                <input type="text" class="form-control form-input" placeholder="{{__('messages.searchGeneral')}}">
                <span class="left-pan"><i class="fa fa-microphone"></i></span>
              </div>
              
            </div>

            
          </div>
        </div>
        <div class="d-flex me-2">
            @foreach($languages as $key => $val)
                <a href="{{route('language.switch',$val->id)}}"><img class="language-item {{($val->current ==1) ? 'active' : ''}} "src="{{$val->image}}" alt=""></a>
            
            @endforeach
        </div>

        <div class="header_img">
          @if(session('avatar'))  <!-- Kiểm tra xem avatar có tồn tại trong session không -->
              <img src="{{ asset(  session('avatar')) }}" alt="Avatar" class="img-fluid rounded-circle">
          @else
              <img src="{{ asset('images/default-avatar.png') }}" alt="Default Avatar" class="img-fluid rounded-circle">
          @endif
      </div>
      
      
  </header>
 
