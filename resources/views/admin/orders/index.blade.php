@extends('admin.layouts.admin')

@section('content')

    <div class="main-content">
        <div class="section__content section__content--p30">
            @if (session('status'))
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="alert alert-info" role="alert">
                                {{ session('status') }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link @if(URL::current() === route('admin.orders','new')) active @endif" href="{{route('admin.orders','new')}}">{{__('Новые заказа')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if(URL::current() === route('admin.orders','old')) active @endif" href="{{route('admin.orders','old')}}">{{__('Старые заказы')}}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-12">
                        <div class="table-responsive table--no-card m-b-30">
                            <table class="table table-borderless table-striped table-earning">
                                <thead>
                                <tr>
                                    <th>Дата</th>
                                    <th>ID заказа</th>
                                    <th>клиент</th>
                                    <th class="text-right">Общяя цена</th>
                                    <th class="text-right">Статус заказа</th>
                                </tr>
                                </thead>
                                <tbody>
                                @isset($orders)
                                    @forelse($orders as $item)
                                        <tr>
                                            <td>
                                                <span class="m-r-10">
                                                    <a href="{{route('admin.order_edit',$item->id)}}">
                                                         <i class="fa fa-pencil-square-o" aria-hidden="true" style="cursor: pointer" title="{{__('Редактировать заказ')}}"></i>
                                                    </a>
                                                </span>
                                                <span class="m-r-10">
                                                    <i data-toggle="modal" data-target="#orderInfo" onclick="getOrderInfo({{$item->id}})" class="fa fa-info" style="cursor: pointer"></i>
                                                </span>
                                                {{$item->updated_at}}
                                            </td>
                                            <td>
                                                @php
                                                    $orderPay = \App\OrderPay::where([
                                                        ['cart_id',$item->id],
                                                        ['user_id',(int)$item->user_id]
                                                    ])->first();
                                                @endphp
                                                @isset($orderPay)
                                                    @if($orderPay->success_pay === 'true')
                                                        <span class="m-r-10">
                                                            <i class="fa fa-usd" aria-hidden="true" title="{{__('Оплачен')}}"></i>
                                                        </span>
                                                    @endif
                                                @endisset
                                                {{$item->id}}
                                            </td>
                                            <td>{{$item->name}}</td>
                                            <td class="text-right">&#8372; {{isset($item->percent)?(round($item->total_price - ($item->total_price*$item->percent/100),2)):$item->total_price}}</td>
                                            <td style="padding: 12px 0;">
                                                <div style="width: 90%;" class="rs-select2--dark rs-select2--md m-r-10 rs-select2--border">
                                                    <select class="js-select2" name="order_status_code" onchange="orderStatus({{$item->id}},this)">
                                                        @isset($order_code)
                                                            @foreach($order_code as $v)
                                                                <option @if($v->id === $item->oder_status) selected @endif value="{{$v->id}}">{{$v->name}}</option>
                                                            @endforeach
                                                        @endisset
                                                    </select>
                                                    <div class="dropDownSelect2"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">
                                                <div class="alert alert-warning" role="alert">
                                                    <p></p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                @endisset

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        {{$orders->links()}}
                    </div>
                </div>
            </div>
        </div>
        @component('admin.component.footer')@endcomponent
    </div>

    <!-- modal order info -->
    @include('admin.orders.partrials.modal_order_info')
    <!-- end modal order info -->

    <script>
        $(function($){
            $(document).mouseup(function (e){
                const div = $("#stock_product");
                if (!div.is(e.target)
                    && div.has(e.target).length === 0) {
                    div.hide();
                }
            });
        });

        function getOrderInfo(id) {
            $('#orderInfoTitle').text(`Информация про заказ №${id}`);
            $.get(`{{route('admin.product.full_order_info')}}?idOrder=${id}`,function (data) {
                let data_str = '';
                data.response.forEach(function (item) {
                    data_str += `
                                 <tr>
                                    <td class="identification-wrapper">${item.id}</td>
                                    <td>${item.articles}</td>
                                    <td>${item.name}</td>
                                    <td>${item.price}</td>
                                    <td>${item.count_in_cart}</td>
                                </tr>
                    `;
                });
                $('#dataOrder').html(data_str);
            });
        }
    </script>

@endsection
