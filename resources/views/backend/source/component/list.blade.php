<div class="d-flex flex-column">
    
    <div class="bg-white  me-3">
        <div class="source-title pt-4 ps-3 pe-3">
            <h5 class=" mainColor">THÔNG TIN NGUỒN KHÁCH</h5>
            
        <hr>
        </div>
        <div class="sourceContent">
            @include('backend.dashboard.component.content',['offTitle'=> TRUE, 'model'=> ($source ?? null)])
        </div>
    </div> 

       

     
     
</div> 
