

@include('backend.dashboard.component.breadcumb',['title' => $config['seo']['index']['title']])

<div class="row">
    <div class="col-lg-12 mt20">
        <div class="ibox ">
          <div class="ibox-title">
            <h5>{{ $config['seo']['index']['table'] }}</h5>
          </div>
            <div class="ibox-content">
               
                @include('backend.permission.component.filter')
                
                @include('backend.permission.component.table')
    
            </div>
    </div>
</div>
