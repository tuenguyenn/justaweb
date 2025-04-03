<div class="bg-white p-4 rounded mb-2">
    <div class="row">
      <div class="col-lg-12">
        <h2 class="mb-3 mainColor">{{ $title }}</h2>

        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-white rounded">
            <li class="breadcrumb-item">
              <a href="{{route('dashboard.index')}}" class="text-decoration-none text-secondary">{{ __('messages.dashboard') }}</a>
            </li>
            <li class="breadcrumb-item  text-dark" aria-current="page">
              <strong>{{ $title }}</strong>
            </li>
          </ol>
        </nav>
      </div>
  </div>
</div>
