<footer class="footer">
    <div class="uk-container uk-container-center">
        <div class="footer-upper">
            <div class="uk-grid uk-grid-medium">
                <div class="uk-width-large-1-5">
                    <div class="footer-contact">
                        <a href="" class="image img-scaledown"><img src="https://themepanthers.com/wp/nest/d1/wp-content/uploads/2022/02/logo.png" alt=""></a>
                        <div class="footer-slogan">Awesome grocery store website template</div>
                        <div class="company-address">
                            <div class="address">{{__('frontend.address')}}</div>
                            <div class="phone">{{__('frontend.hotline')}} </div>
                            <div class="email">{{__('frontend.email')}}</div>
                            <div class="hour">{{__('frontend.workhour')}}</div>
                        </div>
                    </div>
                </div>
                @if (isset($menu['menu-footer']) && count($menu['menu-footer']))

                <div class="uk-width-large-3-5">
                    <div class="footer-menu">
                        <div class="uk-grid uk-grid-medium">
                            @foreach ($menu['menu-footer'] as $key => $val)

                            @php
                                $name = $val['item']->languages->first()->pivot->name;
                                $canonical = write_url($val['item']->languages->first()->pivot->canonical);
                            @endphp
                            <div class="uk-width-large-1-4">
                                <div class="ft-menu">
                                    <div class="heading">{{$name}}</div>
                                  
                                    @if (isset($val['children']) && count($val['children']))
                                        
                                    <ul class="uk-list uk-clearfix">
                                        @foreach ($val['children'] as $key => $childMenu)
                                            @php
                                              
                                                $childMenuName = $childMenu['item']->languages->first()->pivot->name;
                                                $childMenuCanonical = write_url($childMenu['item']->languages->first()->pivot->canonical);

                                            @endphp
                                              <li><a href="{{$childMenuCanonical}}">{{$childMenuName}}</a></li>
                                        @endforeach
                                 
                                    </ul>
                                    @endif
                                   
                                      
                                  
                              

                                </div>
                            </div>
                            @endforeach

                        </div>
                    </div>
                </div>
                @endif
           
                <div class="uk-width-large-1-5">
                    <div class="fanpage-facebook">
                        <div class="ft-menu">
                            <div class="heading">Fanpage Facebook</div>
                            <div class="fanpage">
                                <div class="fb-page" data-href="https://www.facebook.com/facebook" data-tabs="" data-width="" data-height="" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/facebook" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/facebook">Facebook</a></blockquote></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright">
        <div class="uk-container uk-container-center">
            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                <div class="copyright-text">© 2025, Tue Nguyen.<br> All rights reserved</div>
                <div class="copyright-contact">
                    <div class="uk-flex uk-flex-middle">
                        <div class="phone-item">
                            <div class="p">{{__('frontend.hotline')}}</div>
                            <div class="worktime">{{__('frontend.workhour')}}</div>
                        </div>
                        <div class="phone-item">
                            <div class="p">Support: 0965 763 389</div>
                            <div class="worktime"></div>
                        </div>
                    </div>
                </div>
                <div class="social">
                    <div class="uk-flex uk-flex-middle">
                        <div class="span">Follow us:</div>
                        <div class="social-list">
                            <div class="uk-flex uk-flex-middle">
                                <a href="" class=""><i class="fa fa-facebook"></i></a>
                                <a href="" class=""><i class="fa fa-twitter"></i></a>
                                <a href="" class=""><i class="fa fa-skype"></i></a>
                                <a href="" class=""><i class="fa fa-youtube"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>