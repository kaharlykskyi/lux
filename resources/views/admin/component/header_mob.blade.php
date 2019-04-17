<header class="header-mobile d-block d-lg-none">
    <div class="header-mobile__bar">
        <div class="container-fluid">
            <div class="header-mobile-inner">
                <a class="logo" href="{{route('admin.dashboard')}}">
                    <img style="width: 70%;" src="{{asset('images/logo.png')}}" alt="make cars" />
                </a>
                <button class="hamburger hamburger--slider" type="button">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                </button>
            </div>
        </div>
    </div>
    <nav class="navbar-mobile">
        <div class="container-fluid">
            <ul class="navbar-mobile__list list-unstyled">
                <li class="has-sub">
                    <a class="js-arrow" href="#">
                        <i class="fa fa-list-alt" aria-hidden="true"></i>{{__('Каталог')}}</a>
                    <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                        <li>
                            <a href="{{route('admin.product.index')}}">{{__('Товары')}}</a>
                        </li>
                        <li>
                            <a href="{{route('admin.product.popular')}}">{{__('Популярные товары')}}</a>
                        </li>
                        <li>
                            <a href="{{route('admin.category.index')}}">{{__('Категории')}}</a>
                        </li>
                        <li>
                            <a href="{{route('admin.show_brand')}}">{{__('Бренды')}}</a>
                        </li>
                        <li>
                            <a href="{{route('admin.filter','use')}}">{{__('Настрайки фильтра')}}</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{route('admin.feedback')}}">
                        <i class="fa fa-comments" aria-hidden="true"></i>{{__('Обратная связь')}}
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.users_cart')}}">
                        <i class="fa fa-cart-arrow-down" aria-hidden="true"></i>{{__('Корзина заказчиков')}}
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.pay_mass')}}">
                        <i class="fa fa-usd" aria-hidden="true"></i>{{__('Сообщения об оплате')}}
                        @if(isset($count_new_pay_mass_global) && $count_new_pay_mass_global > 0)
                            (<span class="text-danger">{{$count_new_pay_mass_global}}</span>)
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.users')}}">
                        <i class="zmdi zmdi-account-o"></i>{{__('Пользователи')}}
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.discount.index')}}">
                        <i class="fa fa-sort-numeric-desc" aria-hidden="true"></i>{{__('Скидки')}}
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.call_orders')}}">
                        <i class="fa fa-phone" aria-hidden="true"></i>
                        {{__('Заказ звонка')}}
                        @if(isset($count_new_call_orders_global) && $count_new_call_orders_global > 0)
                            (<span class="text-danger">{{$count_new_call_orders_global}}</span>)
                        @endif
                    </a>
                </li>
                <li class="has-sub">
                    <a class="js-arrow" href="#">
                        <i class="fa fa-users" aria-hidden="true"></i></i>{{__('Поставщики')}}</a>
                    <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                        <li>
                            <a href="{{route('admin.provider.index')}}">
                                {{__('Все поставщики')}}
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin.pro_file.index')}}">
                                {{__('Профайлы прайсов')}}
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin.import_history')}}">{{__('История импорта')}}</a>
                        </li>
                    </ul>
                </li>
                <li class="has-sub">
                    <a class="js-arrow" href="#">
                        <i class="zmdi zmdi-shopping-cart"></i>{{__('Заказы')}}</a>
                    <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                        <li>
                            <a href="{{route('admin.orders')}}">
                                {{__('Заказы')}}
                                @if(isset($count_new_orders_global) && $count_new_orders_global > 0)
                                    (<span class="text-danger">{{$count_new_orders_global}}</span>)
                                @endif
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin.fast_buy','new')}}">
                                {{__('Быстрая покупка')}}
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="has-sub">
                    <a class="js-arrow" href="#">
                        <i class="fas fa-copy"></i>{{__('Управление контентом')}}</a>
                    <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                        <li>
                            <a href="{{route('admin.page.index')}}">{{__('Страници')}}</a>
                        </li>
                        <li>
                            <a href="{{route('admin.banner.index')}}">{{__('Слайды баннера')}}</a>
                        </li>
                        <li>
                            <a href="{{route('admin.menu.index')}}">{{__('Меню')}}</a>
                        </li>
                        <li>
                            <a href="{{route('admin.comment')}}">{{__('Коментарии')}}</a>
                        </li>
                        <li>
                            <a href="{{route('admin.shipping_payment')}}">{{__('Доставка и оплата')}}</a>
                        </li>
                        <li>
                            <a href="{{route('admin.advertising')}}">{{__('Настройка рекламы')}}</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
