<aside class="menu-sidebar d-none d-lg-block">
    <div class="logo" style="overflow: hidden;">
        <a href="{{route('admin.dashboard')}}">
            <img style="width: 80%;" src="{{asset('images/logo.png')}}" alt="make cars" />
        </a>
    </div>
    <div class="menu-sidebar__content js-scrollbar1">
        <nav class="navbar-sidebar">
            <ul class="list-unstyled navbar__list">
                <li class="has-sub">
                    <a class="js-arrow" href="#">
                        <i class="fa fa-list-alt" aria-hidden="true"></i>{{__('Каталог')}}</a>
                    <ul class="list-unstyled navbar__sub-list js-sub-list">
                        <li>
                            <a href="{{route('admin.category.index')}}">{{__('Категории для типов машин')}}</a>
                            <a href="{{route('admin.car_categories.index')}}">{{__('Групировка категорий для машин')}}</a>
                            <a href="{{route('admin.product.index')}}">{{__('Товары')}}</a>
                            <a href="{{route('admin.no_brands.products')}}">
                                {{__('Несоотвествия бренда')}}
                                @if(isset($count_no_brands_global) && $count_no_brands_global > 0)
                                    (<span class="text-danger">{{$count_no_brands_global}}</span>)
                                @endif
                            </a>
                            <a href="{{route('admin.product.popular')}}">{{__('Популярные товары')}}</a>
                            <a href="{{route('admin.show_brand')}}">{{__('Бренды')}}</a>
                            <a href="{{route('admin.filter','use')}}">{{__('Настройки фильтра')}}</a>
                            <a href="{{route('admin.all_category.index')}}">{{__('Общая структура категорий')}}</a>
                            <a href="{{route('admin.home_category.index')}}">{{__('Групировка категорий для главной страницы')}}</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{route('admin.feedback')}}">
                        <i class="fa fa-comments" aria-hidden="true"></i>{{__('Обратная связь')}}
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
                <li>
                    <a href="{{route('admin.users_cart')}}">
                        <i class="fa fa-cart-arrow-down" aria-hidden="true"></i>{{__('Корзина заказчиков')}}
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.sto_manager.index')}}">
                        <i class="fa fa-address-card" aria-hidden="true"></i>{{__('СТО база')}}
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
                    <a href="{{route('admin.orders')}}">
                        <i class="zmdi zmdi-shopping-cart"></i>{{__('Заказы')}}
                        @if(isset($count_new_orders_global) && $count_new_orders_global > 0)
                            (<span class="text-danger">{{$count_new_orders_global}}</span>)
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.fast_buy','new')}}">
                        <i class="fa fa-fighter-jet" aria-hidden="true"></i>{{__('Быстрая покупка')}}
                    </a>
                </li>
                <li class="has-sub">
                    <a class="js-arrow" href="#">
                        <i class="fa fa-users" aria-hidden="true"></i></i>{{__('Поставщики')}}</a>
                    <ul class="list-unstyled navbar__sub-list js-sub-list">
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
                <li>
                    <a href="{{route('admin.discount.index')}}">
                        <i class="fa fa-sort-numeric-desc" aria-hidden="true"></i>{{__('Скидки')}}
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.cross.index')}}">
                        <i class="fa fa-cogs" aria-hidden="true"></i>{{__('Расширение кроссов')}}
                    </a>
                </li>
                <li class="has-sub">
                    <a class="js-arrow" href="#">
                        <i class="fas fa-copy"></i>{{__('Управление контентом')}}</a>
                    <ul class="list-unstyled navbar__sub-list js-sub-list">
                        <li>
                            <a href="{{route('admin.page.index')}}">{{__('Страницы')}}</a>
                        </li>
                        <li>
                            <a href="{{route('admin.banner.index')}}">{{__('Слайды баннера')}}</a>
                        </li>
                        <li>
                            <a href="{{route('admin.top_menu.index')}}">{{__('Меню')}}</a>
                        </li>
                        <li>
                            <a href="{{route('admin.comment')}}">{{__('Комментарии')}}</a>
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
        </nav>
    </div>
</aside>
