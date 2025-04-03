  @php
      $name = $model->languages->first()->pivot->name;
  @endphp
  <div class="page-breadcum">
        <ul class="uk-list uk-clearfix">
            <li><a href="/"><i class="fi-rs-home mr5"></i> {{__('messages.dashboard')}}</a></li>
            @if (!is_null($breadcumb))
            @foreach ($breadcumb as $item)
                @php
                    $name = $item->languages->first()->pivot->name;
                    $canonical = write_url($item->languages->first()->pivot->canonical,true,true)
                @endphp
                <li><a href="{{$canonical}}" class=""> {{$name}}</a></li>

            @endforeach
                
            @endif
           
        </ul>
    </div>