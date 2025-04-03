

@include('backend.dashboard.component.breadcumb',['title' => $config['seo']['index']['title']])

<div class="row">
    <div class="col-lg-12 ">
        <div class="ibox  rounded">
          <div class="bg-white p-3 rounded">
            <h4 class="mainColor">{{ $config['seo']['index']['table'] }}</h4>
            @include('backend.dashboard.component.toolbox',['model'=>'Promotion'])
          </div>
            <div class="ibox-content ">
               
                @include('backend.dashboard.component.filter',['model'=>'promotion'])
                
                @include('backend.promotion.promotion.component.table')
                @include('backend.dashboard.component.modal')

            </div>
    </div>
</div>
