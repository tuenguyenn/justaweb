<div class="panel-banner">
    <div class="uk-container uk-container-center">
        <div class="panel-body">
            <div class="uk-grid uk-grid-medium">
                @if (count($slides['banner']->slideItems))
                @foreach ($slides['banner']->slideItems as $key => $val)
                @php
                    $name = $val['name'];
                    $canonical = write_url($val['canonical'],true,true);
                    $image =  $val['image'];
                @endphp
                <div class="uk-width-large-1-3">
                    <div class="banner-item">
                        <span class="image"><img src="{{$image}}" alt=""></span>
                        <div class="banner-overlay">
                            <div class="banner-title">{!!$name!!}</div>
                            <a class="btn-shop" title="" href="{{$canonical}}">Mua ngay</a>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>
</div>