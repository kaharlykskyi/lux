<header class="header-desktop">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="header-wrap">
                <div class="form-header">
                    <a class="btn btn-primary" href="{{route('admin.cache.clear')}}" role="button">Очистить кеш сайта</a>
                    <a class="btn btn-default m-l-15" href="{{route('admin.sitemap')}}" role="button">
                        @if(Illuminate\Support\Facades\File::exists(public_path('sitemap.xml')))
                            Обновить sitemap
                        @else
                            Создать sitemap
                        @endif
                    </a>
                </div>
                <div class="header-button" style="justify-content: flex-end;">
                    <div class="account-wrap">
                        <div class="account-item clearfix js-item-menu">
                            <div class="content">
                                <a class="js-acc-btn" href="#">{{Auth::user()->fio}}</a>
                            </div>
                            <div class="account-dropdown js-dropdown">
                                <div class="account-dropdown__body">
                                    <div class="account-dropdown__item">
                                        <a href="{{route('profile')}}" target="_blank">
                                            <i class="zmdi zmdi-account"></i>{{__('Профиль')}}</a>
                                    </div>
                                    <div class="account-dropdown__item">
                                        <a href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            <i class="zmdi zmdi-power"></i>{{__('Выйти')}}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
