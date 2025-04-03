
@foreach($languages as $key => $val)
@if(session('app_locale') == $val->canonical) 
@continue;
@endif

<th class="text-center" ><span><img class="img-translate" src="{{asset($val->image)}}" alt=""></span></th>

@endforeach