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
                            <a href="{{route('admin.category.index')}}">{{__('Категории')}}</a>
                            <a href="{{route('admin.product.index')}}">{{__('Товары')}}</a>
                            <a href="{{route('admin.show_brand')}}">{{__('Бренды')}}</a>
                            <a href="{{route('admin.import_history')}}">{{__('История импорта')}}</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{route('admin.feedback')}}">
                        <i class="fa fa-comments" aria-hidden="true"></i>{{__('Обратная связь')}}
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.users')}}">
                        <i class="zmdi zmdi-account-o"></i>{{__('Пользователи')}}
                    </a>
                </li>
                <li class="has-sub">
                    <a class="js-arrow" href="#">
                        <i class="zmdi zmdi-shopping-cart"></i>{{__('Заказы')}}</a>
                    <ul class="list-unstyled navbar__sub-list js-sub-list">
                        <li>
                            <a href="{{route('admin.orders','new')}}">
                                {{__('Заказы')}}
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin.fast_buy','new')}}">
                                {{__('Быстрая покупка')}}
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{route('admin.discount.index')}}">
                        <i class="fa fa-sort-numeric-desc" aria-hidden="true"></i>{{__('Скидки')}}
                    </a>
                </li>
                <li class="has-sub">
                    <a class="js-arrow" href="#">
                        <i class="fas fa-copy"></i>{{__('Управление контентом')}}</a>
                    <ul class="list-unstyled navbar__sub-list js-sub-list">
                        <li>
                            <a href="{{route('admin.page.index')}}">{{__('Страници')}}</a>
                        </li>
                        <li>
                            <a href="{{route('admin.banner.index')}}">{{__('Слайды баннера')}}</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>