

@include('backend.dashboard.component.breadcumb',['title' => $config['seo']['index']['title']])


<div class="row">
    <div class="col-lg-12 mt20">
        <div class="ibox ">
          <div class="ibox-title">
            <h5>{{ $config['seo']['index']['table'] }}</h5>
            @include('backend.dashboard.component.toolbox',['model'=>'Post'])
          </div>
            <div class="ibox-content">
               
              @include('backend.dashboard.component.filter',['model'=>'post'])
                
                @include('backend.post.post.component.table')
                @include('backend.dashboard.component.modal')

            </div>

    </div>
</div>
