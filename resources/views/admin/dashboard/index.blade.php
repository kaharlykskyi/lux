@extends('admin.layouts.admin')

@section('content')

    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="row m-t-25">
                    <div class="col-sm-6 col-lg-4">
                        <a href="{{route('admin.users')}}">
                            <div class="overview-item overview-item--c1">
                                <div class="overview__inner">
                                    <div class="overview-box clearfix">
                                        <div class="icon">
                                            <i class="zmdi zmdi-account-o"></i>
                                        </div>
                                        <div class="text">
                                            <h2>{{$users_count}}</h2>
                                            <span>{{__('Пользователей')}}</span>
                                        </div>
                                    </div>
                                    <div class="overview-chart">
                                        <canvas id="widgetChart1"></canvas>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <a href="{{route('admin.orders')}}">
                            <div class="overview-item overview-item--c2">
                                <div class="overview__inner">
                                    <div class="overview-box clearfix">
                                        <div class="icon">
                                            <i class="zmdi zmdi-shopping-cart"></i>
                                        </div>
                                        <div class="text">
                                            <h2>{{$orders_count}}</h2>
                                            <span>{{__('заказы')}}</span>
                                        </div>
                                    </div>
                                    <div class="overview-chart">
                                        <canvas id="widgetChart2"></canvas>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <a href="{{route('admin.fast_buy','new')}}">
                            <div class="overview-item overview-item--c3">
                                <div class="overview__inner">
                                    <div class="overview-box clearfix">
                                        <div class="icon">
                                            <i class="zmdi zmdi-calendar-note"></i>
                                        </div>
                                        <div class="text">
                                            <h2>{{$fast_buy_count}}</h2>
                                            <span>{{__('быстрая покупка')}}</span>
                                        </div>
                                    </div>
                                    <div class="overview-chart">
                                        <canvas id="widgetChart3"></canvas>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                @component('admin.component.footer')@endcomponent
            </div>
        </div>
    </div>

@endsection
