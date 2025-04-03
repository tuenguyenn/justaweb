
<script src="backend/library/library.js" ></script>
<script src="backend/library/sidebar.js" ></script>
<script src="backend/library/modal.js" ></script>
<script src="backend/js/plugins/jquery-ui/jquery-ui.min.js"></script>

<script src="backend/plugins/nice_select/js/jquery.nice-select.min.js"></script>
<script src="backend/js/popper.min.js"></script>
<script src="backend/js/bootstrap.js"></script>
<script src="backend/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="backend/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>



<script>
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right",
        timeOut: "3000"
    };
</script>


<!-- Flot -->
@if(@isset($config['js']) && is_array($config['js']))
    @foreach ($config['js'] as $key => $val)
        {!! '<script src="'.$val.'"></script>' !!}
    @endforeach    
@endif
  
