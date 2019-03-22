<footer>
    <div class="container">

        <!-- Footer Upside Links -->
        <div class="foot-link">
        </div>
        <div class="row">

            <!-- Contact -->
            <div class="col-md-4">
                <h4>Контакты {{config('app.name')}}!</h4>
                <p>{{__('Адресс')}}: {{config('app.company_location')}}</p>
                <p>{{__('Телефон')}}: {{config('app.company_phone')}}</p>
                <p>{{__('E-mail')}}: {{config('app.work_mail')}}</p>
                <div class="social-links"> <a href="#."><i class="fa fa-facebook"></i></a> <a href="#."><i class="fa fa-twitter"></i></a> <a href="#."><i class="fa fa-linkedin"></i></a> <a href="#."><i class="fa fa-pinterest"></i></a> <a href="#."><i class="fa fa-instagram"></i></a> <a href="#."><i class="fa fa-google"></i></a> </div>
            </div>
            <div class="col-md-4">
                <h4>{{__('Для клиента')}}</h4>
                <ul class="links-footer">
                    @isset($pages)
                        @foreach($pages as $page)
                            @if($page->footer_column === 1)
                                <li><a href="{{route('page',$page->alias)}}">{{$page->title}}</a></li>
                            @endif
                        @endforeach
                    @endisset
                </ul>
            </div>
            <div class="col-md-4">
                <h4>{{__('Информация')}}</h4>
                <ul class="links-footer">
                    @isset($pages)
                        @foreach($pages as $page)
                            @if($page->footer_column === 2)
                                <li><a href="{{route('page',$page->alias)}}">{{$page->title}}</a></li>
                            @endif
                        @endforeach
                    @endisset
                </ul>
            </div>
        </div>
    </div>
</footer>

<!-- Rights -->
<div class="rights">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <p>Copyright © {{date('Y')}} <a href="#." class="ri-li"> {{config('app.name')}} </a>. Все права защищены</p>
            </div>
            <div class="col-sm-6 text-right"> <img src="{{asset('images/card-icon.png')}}" alt=""> </div>
        </div>
    </div>
</div>

<!-- GO TO TOP  -->
<a href="#" class="cd-top"><i class="fa fa-angle-up"></i></a>
<!-- GO TO TOP End -->
