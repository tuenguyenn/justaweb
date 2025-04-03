

@include('backend.dashboard.component.breadcumb',['title' => $config['seo']['index']['title']])

<div class="row">
    <div class="col-lg-12 ">
        <div class="ibox border rounded">
          <div class="bg-white p-3 rounded">
            <h3>{{ $config['seo']['index']['table'] }}</h3>
            @include('backend.dashboard.component.toolbox',['model'=>'User'])
          </div>
            <div class="ibox-content ">
               
                @include('backend.dashboard.component.filter',['model'=>'user'])
                
                @include('backend.user.user.component.table')
                @include('backend.dashboard.component.modal')

            </div>
    </div>
</div>
