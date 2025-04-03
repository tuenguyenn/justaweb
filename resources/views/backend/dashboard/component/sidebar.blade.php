<div class="l-navbar" id="nav-bar">
    <nav class="nav">
        <div>
            <a href="{{route('dashboard.index')}}" class="nav_logo"> 
                <i class='bx bx-layer nav_logo-icon'></i> 
                <span class="nav_logo-name">ADMIN</span>    
            </a>
            <div class="nav_list">
                @foreach (__('sidebar.module') as $key => $module)
                    @if (isset($module['subModule']) && count($module['subModule']) > 0)
                        <div>
                            <a href="#submenu{{$key}}" data-bs-toggle="collapse" class="nav_link">
                                <i class="{{ $module['icon'] }}"></i>
                                <span class="nav_name">{{ $module['title'] }}</span>
                            </a>
                        </div>
                        <div>
                            <ul class="collapse nav flex-column ms-3" id="submenu{{$key}}" data-bs-parent="#menu">
                                @foreach ($module['subModule'] as $subModule)
                                    <li>
                                        <a href="{{ route($subModule['route']) }}" class="item-sidebar " >
                                            {{ $subModule['title'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <a href="{{ route($module['route']) }}" class="nav_link">
                            <i class="{{ $module['icon'] }}"></i>
                            <span class="nav_name">{{ $module['title'] }}</span>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
        <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav_link">
            <i class='bx bx-log-out nav_icon'></i> 
            <span class="nav_name">{{ __('messages.logout') }}</span>
        </a>
        
    </nav>
</div>

  <style>
  
    .header {
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1); 
        border-bottom: 1px solid var(--first-color-light); 
    }



    @import url("https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap");:root{--header-height: 3rem;--nav-width: 68px;--first-color: #405D72;--first-color-light:#D4EBF8 ;--white-color: #F7F6FB;--body-font: 'Open Sans', sans-serif;--normal-font-size: 1rem;--z-fixed: 100}*,::before,::after{box-sizing: border-box}body{position: relative;margin: var(--header-height) 0 0 0;padding: 0 1rem;font-family: var(--body-font);font-size: var(--normal-font-size);transition: .5s}a{text-decoration: none}.header{width: 100%;height: var(--header-height);position: fixed;top: 0;left: 0;display: flex;align-items: center;justify-content: space-between;padding: 0 1rem;background-color: var(--white-color);z-index: var(--z-fixed);transition: .5s}.header_toggle{color: var(--first-color);font-size: 1.5rem;cursor: pointer}.header_img{width: 35px;height: 35px;display: flex;justify-content: center;border-radius: 50%;overflow: hidden}.header_img img{width: 40px}.l-navbar{position: fixed;top: 0;left: -30%;width: var(--nav-width);height: 100vh;background-color: var(--first-color);padding: .5rem 1rem 0 0;transition: .5s;z-index: var(--z-fixed)}.nav{height: 100%;display: flex;flex-direction: column;justify-content: space-between;overflow: hidden}.nav_logo, .nav_link{display: grid;grid-template-columns: max-content max-content;align-items: center;column-gap: 1rem;padding: .5rem 0 .5rem 1.5rem}.nav_logo{margin-bottom: 2rem}.nav_logo-icon{font-size: 1.25rem;color: var(--white-color)}.nav_logo-name{color: var(--white-color);font-weight: 700}.nav_link{position: relative;color: var(--first-color-light);margin-bottom: 1.5rem;}.nav_link:hover{color: var(--white-color)}.nav_icon{font-size: 1.25rem}.show{left: 0}.body-pd{padding-left: calc(var(--nav-width) + 1rem)}.active{color: var(--white-color)}.active::before{content: '';position: absolute;left: 0;width: 2px;height: 32px;background-color: var(--white-color)}.height-100{height:100vh}@media screen and (min-width: 768px){body{margin: calc(var(--header-height) + 1rem) 0 0 0;padding-left: calc(var(--nav-width) + 2rem)}.header{height: calc(var(--header-height) + 1rem);padding: 0 2rem 0 calc(var(--nav-width) + 2rem)}.header_img{width: 40px;height: 40px}.header_img img{width: 45px}.l-navbar{left: 0;padding: 1rem 1rem 0 0}.show{width: calc(var(--nav-width) + 156px)}.body-pd{padding-left: calc(var(--nav-width) + 188px)}}
  </style>
 