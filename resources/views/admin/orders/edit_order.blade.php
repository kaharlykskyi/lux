@extends('admin.layouts.admin')

@section('content')

    <div class="container-fluid m-t-75">
        @if (session('status'))
            <div class="row">
                <div class="col-sm-12">
                    <div class="alert alert-info" role="alert">
                        {{ session('status') }}
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-12 m-b-15 m-t-15">
                <a href="{{route('admin.orders','new')}}" class="btn btn-success">{{__('Назад')}}</a>
            </div>
            <div class="col-12 m-t-10 m-b-10">
                <h3 class="pb-2 display-5">{{__('Заказ №') . $order->id}}</h3>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">{{__('Данные заказа')}}
                            @isset($order_pay)
                                @if($order_pay->success_pay === 'true')
                                    <small>
                                        <span class="badge badge-success float-right mt-1">{{__('оплачен')}}</span>
                                    </small>
                                @endif
                            @endisset
                        </strong>
                    </div>
                    <div class="card-body">
                        @isset($user)
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th>{{__('Имя')}}</th>
                                            <td>{{$user->name}}</td>
                                        </tr>
                                        <tr>
                                            <th>{{__('E-mail')}}</th>
                                            <td>{{$user->email}}</td>
                                        </tr>
                                        <tr>
                                            <th>{{__('Телефон')}}</th>
                                            <td>{{$user->phone}}</td>
                                        </tr>
                                        <tr>
                                            <th>{{__('Страна')}}</th>
                                            <td>{{isset($user->deliveryInfo->delivery_country)?$user->deliveryInfo->delivery_country:$user->country}}</td>
                                        </tr>
                                        <tr>
                                            <th>{{__('Город')}}</th>
                                            <td>{{isset($user->deliveryInfo->delivery_city)?$user->deliveryInfo->delivery_city:$user->city}}</td>
                                        </tr>
                                        <tr>
                                            <th>{{__('Служба доставки')}}</th>
                                            <td>{{trans('custom.'.$user->deliveryInfo->delivery_service)}}</td>
                                        </tr>
                                        <tr>
                                            <th>{{__('Отделение почты')}}</th>
                                            <td>{{$user->deliveryInfo->delivery_department}}</td>
                                        </tr>
                                        <tr>
                                            <th>{{__('Номер накладной')}}</th>
                                            <td>
                                                <div class="form-group">
                                                    <input onblur="saveInvoice('{{$order->id}}',this)" name="invoice_np" type="text" class="form-control" aria-required="true" aria-invalid="false" value="{{$order->invoice_np}}">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{__('Статус заказа')}}</th>
                                            <td>
                                                <div style="width: 90%;" class="rs-select2--dark rs-select2--md m-r-10 rs-select2--border">
                                                    <select class="js-select2" name="order_status_code" onchange="orderStatus({{$order->id}},this)">
                                                        @isset($order_code)
                                                            @foreach($order_code as $v)
                                                                <option @if($v->id === $order->oder_status) selected @endif value="{{$v->id}}">{{$v->name}}</option>
                                                            @endforeach
                                                        @endisset
                                                    </select>
                                                    <div class="dropDownSelect2"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endisset
                    </div>
                </div>
            </div>
            <div class="col-12 m-t-10">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">{{__('Заказаные товары')}}</strong>
                    </div>
                    <div class="card-body">
                        @isset($product)
                            <div class="table-responsive">
                                <table class="table table-borderless table-data3">
                                    <thead>
                                        <tr>
                                            <th>Название</th>
                                            <th>Артикль</th>
                                            <th>Цела</th>
                                            <th>Количество</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($product as $item)
                                            <tr>
                                                <td>
                                                    <span class="m-r-10">
                                                        <i onclick="" class="fa fa-info" style="cursor: pointer" title="Показать аналогичные товары"></i>
                                                    </span>
                                                    {{$item->name}}
                                                </td>
                                                <td>{{$item->articles}}</td>
                                                <td>{{$item->price}}</td>
                                                <td>{{$item->count}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endisset
                    </div>
                </div>
            </div>
        </div>

     @component('admin.component.footer')@endcomponent
    </div>

    <script>
        function stockProductDelivery(id,obg) {
            $.get(`{{route('admin.product.stock')}}?order_id={{$order->id}}&id_product=${id}&id_stock=${$(obg).val()}`,function () {
                alert('Сохранено');
            });
        }
    </script>

@endsection
