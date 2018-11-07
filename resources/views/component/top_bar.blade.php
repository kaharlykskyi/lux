<div class="top-bar">
    <div class="container">
        <p class="text-uppercase">Добро пожаловать!</p>
        <div class="right-sec">
            <ul>
                <!-- Authentication Links -->
                @guest
                    <li>
                        <a href="{{ route('login') }}">{{ __('Войти') }}</a>
                    </li>
                    <li>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">{{ __('Регистрация') }}</a>
                        @endif
                    </li>
                @else
                    <li class="btn-group">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
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
                <li>
                    <select class="selectpicker">
                        <option>(USD)Dollar</option>
                        <option>GBP</option>
                        <option>Euro</option>
                        <option>JPY</option>
                    </select>
                </li>
            </ul>
            <div class="social-top"> <a href="#."><i class="fa fa-facebook"></i></a> <a href="#."><i class="fa fa-twitter"></i></a> <a href="#."><i class="fa fa-linkedin"></i></a> <a href="#."><i class="fa fa-dribbble"></i></a> <a href="#."><i class="fa fa-pinterest"></i></a> </div>
        </div>
    </div>
</div>