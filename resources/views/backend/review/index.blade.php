

@include('backend.dashboard.component.breadcumb',['title' => $config['seo']['index']['title']])

<div class="row">
    <div class="col-lg-12 ">
        <div class="ibox border rounded">
          <div class="bg-white rounded">
         
            @include('backend.dashboard.component.toolbox',['model'=>'Review'])
          </div>
            <div class="ibox-content border-0">
               
                
                @include('backend.review.component.table')
                @include('backend.dashboard.component.modal')

            </div>
    </div>
</div>
