@if (!is_null($widgets['cate-2']))
<div class="category-children">
    <ul class="uk-list uk-clearfix uk-flex uk-flex-middle">
        @foreach ($widgets['cate-2']->objects as $val)
        @php
            $name = $val->languages->first()->pivot->name;
            $canonical = write_url($val->languages->first()->pivot->canonical);
        @endphp
        <li class=""><a href="{{$canonical}}" title="{{$name}}">{{$name}}</a></li>
        @endforeach
        
    </ul>
</div>
@endif