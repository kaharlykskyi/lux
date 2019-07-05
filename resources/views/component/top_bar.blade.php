<div class="top-bar">
    <div class="container">
        <div class="col-lg-8">
            <ul class="nav nav-pills">
                <li role="presentation"><a href="{{route('home')}}">{{__('Главная')}}</a></li>
                @isset($pages_global)
                    @foreach($pages_global as $page)
                        @if($page->show_header === 1)
                            <li><a href="{{route('page',$page->alias)}}">{{$page->title}}</a></li>
                        @endif
                    @endforeach
                @endisset
                <li id="mob-phone" class="margin-right-15 relative">
                    <i class="fa fa-phone"></i>
                    <div class="phone-block">
                        <span style="line-height: 1;font-size: 16px">Заказ и подбор запчастей с 09:00 до 20:00</span><br>
                        <span style="line-height: 1;" class="text-primary text-left">{{config('app.company_phone')}}</span><br>
                        <span style="line-height: 1;" class="text-primary text-left">{{__('+38(093)340-10-41')}}</span><br>
                        <span style="line-height: 1;" class="text-primary text-left">{{__('+38(068)708-15-15')}}</span>
                    </div>
                </li>
            </ul>
        </div>
        <div class="col-lg-4 text-right">
            <ul class="reg-link">
                <!-- Authentication Links -->
                <li id="desc-phone" class="margin-right-15 relative">
                    <i class="fa fa-phone"></i>
                    <div class="phone-block">
                        <p style="line-height: 1;font-size: 16px">Заказ и подбор запчастей с 09:00 до 20:00</p>
                        <p style="line-height: 1;" class="text-primary text-left">{{config('app.company_phone')}}</p>
                        <p style="line-height: 1;" class="text-primary text-left">{{__('(+380) 933 401 041')}}</p>
                        <p style="line-height: 1;" class="text-primary text-left">{{__('(+380) 687 081 515')}}</p>
                    </div>
                </li>
                @guest
                    <li class="margin-right-15">
                        <a href="{{ route('login') }}">{{ __('Войти') }}</a>
                    </li>
                    <li>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">{{ __('Регистрация') }}</a>
                        @endif
                    </li>
                @else
                    <li class="btn-group dropdown-list">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            {{ str_limit(Auth::user()->fio,10,'...') }} <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="{{route('profile')}}">{{__('Профиль')}}</a>
                            </li>
                            @if(Auth::user()->permission === 'admin' || Auth::user()->permission === 'manager')
                                <li>
                                    <a href="{{route('admin.dashboard')}}">{{__('Админ панель')}}</a>
                                </li>
                            @endif
                            <li>
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Выйти') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</div>
