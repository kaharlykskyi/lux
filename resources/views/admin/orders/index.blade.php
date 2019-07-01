@extends('admin.layouts.admin')

@section('content')

    <div class="main-content p-t-85">
        <div class="section__content">
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
                        <div class="card">
                            <div class="card-body card-block">
                                <form action="{{route('admin.orders')}}" method="get" id="filter_oder">
                                    <div class="row form-group">
                                        <div class="col col-sm-4">
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="oder_id" class=" form-control-label">Заказ</label>
                                                </div>
                                                <div class="col-12 col-md-9">
                                                    <input type="text" id="oder_id" value="{{request()->query('oder_id')}}" name="oder_id" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col col-sm-4">
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="status_oder" class=" form-control-label">Статус заказа</label>
                                                </div>
                                                <div class="col-12 col-md-9">
                                                    <select name="status_oder" id="status_oder" class="form-control">
                                                        <option value="0"></option>
                                                        @foreach($order_code as $code)
                                                            <option @if(request()->query('status_oder') == $code->id) selected @endif value="{{$code->id}}">{{$code->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col col-sm-4">
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="client_id" class=" form-control-label">Заказчик</label>
                                                </div>
                                                <div class="col-12 col-md-9">
                                                    <select name="client_id" id="client_id" class="form-control">
                                                        <option value="0"></option>
                                                        @foreach($clients as $client)
                                                            <option @if((int)request()->query('client_id') === $client->id) selected @endif value="{{$client->id}}">{{$client->fio}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col col-sm-4">
                                            <div class="row form-group">
                                                <div class="col col-md-8">
                                                    <label for="new_oder" class=" form-control-label">Не просмотренные</label>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <input type="checkbox" @if(request()->has('new_oder')) checked @endif id="new_oder" name="new_oder" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col col-sm-4">
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="date_oder_start"  class=" form-control-label">Дата от</label>
                                                </div>
                                                <div class="col-12 col-md-9">
                                                    <input type="date" value="{{request()->query('date_oder_start')}}" id="date_oder_start" name="date_oder_start" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col col-sm-4">
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="date_oder_end" class=" form-control-label">Дата до</label>
                                                </div>
                                                <div class="col-12 col-md-9">
                                                    <input type="date" value="{{request()->query('date_oder_end')}}" id="date_oder_end" name="date_oder_end" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer">
                                <button onclick="$('#filter_oder').submit();" class="btn btn-primary btn-sm">
                                    <i class="fa fa-dot-circle-o"></i> Фильтровать
                                </button>
                                <button onclick="location.href = '{{route('admin.orders')}}'" class="btn btn-danger btn-sm">
                                    <i class="fa fa-ban"></i> Отменить
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="col-12 m-b-15 text-right">
                            <div class="table-data__tool-right">
                                <button onclick="location.href = '{{route('admin.order.create')}}'" class="au-btn au-btn-icon au-btn--green au-btn--small">
                                    <i class="zmdi zmdi-plus"></i>{{__('Создать Заказ')}}</button>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="alert alert-warning">
                                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                <span class="small">После присвоения заказу статуса "отменен", если он оплачен, средства будут возвращены пользователю на его счёт</span>
                            </div>
                        </div>
                        <div class="table--no-card m-b-30 table-responsive">
                            <table class="table table-borderless table-striped table-earning">
                                <thead>
                                <tr>
                                    <th>ID заказа</th>
                                    <th>Клиент/Дата</th>
                                    <th>Номер / Фирма / Кол-во / Наименование</th>
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
                                                @isset($item->payOder)
                                                    @if($item->payOder->success_pay === 'true')
                                                        <span class="m-r-10">
                                                            <i class="fa fa-usd text-success" aria-hidden="true" title="{{__('Оплачен')}}"></i>
                                                        </span>
                                                    @endif
                                                @endisset
                                                {{$item->id}}
                                            </td>
                                            <td onmouseleave="$('.hover-show').hide()" onmouseenter="setPosition('loc_block_{{$item->id}}')" class="hover-trigger position-relative">
                                                <span id="loc_block_{{$item->id}}">{{$item->client->fio}}</span>
                                                <div data-id="loc_block_{{$item->id}}" class="hover-show">
                                                    <p><strong>ФИО: </strong>{{$item->client->fio}}</p>
                                                    <p><strong>Email: </strong>{{$item->client->email}}</p>
                                                    <p><strong>Тип пользователя: </strong>{{$item->client->type_user->name}}</p>
                                                    <p><strong>Город: </strong>{{isset($item->client->deliveryInfo)?$item->client->deliveryInfo->delivery_city:''}}</p>
                                                    <p><strong>Адрес: </strong>{{isset($item->client->deliveryInfo)?$item->client->deliveryInfo->street . '/' . $item->client->deliveryInfo->house:''}}</p>
                                                    <p><strong>Телефон: </strong>{{$item->client->phone}}</p>
                                                </div><br>
                                                {{$item->oder_dt}}
                                            </td>
                                            <td>
                                                @foreach($item->cartProduct as $product)
                                                    <p style="font-size: 13px">
                                                        {{$product->articles}}
                                                        <strong>
                                                            @if($product->original === 1)
                                                                @foreach($manufacturers as $data)
                                                                    @if($data->id === $product->brand)
                                                                        {{$data->matchcode}}
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                @foreach($suppliers as $data)
                                                                    @if($data->id === $product->brand)
                                                                        {{$data->matchcode}}
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                            [<span class="text-danger">{{$product->pivot->count}}</span>]
                                                        </strong>
                                                        <span class="text-success">{{str_limit($product->name,30)}}</span>
                                                    </p>
                                                @endforeach
                                                <a style="font-size: 12px;" href="{{route('admin.orders',['delete_oder' => $item->id])}}">
                                                    <i class="fa fa-trash" aria-hidden="true"></i> удалить
                                                </a>
                                                @if($item->oder_status !== 5)
                                                    <a onclick="backOrder('{{$item->id}}')" style="font-size: 12px;margin-left: 10px;color: #ff0000" href="javascript:void(0);">
                                                        <i style="margin-right: 5px;" class="fa fa-ban" aria-hidden="true"></i>отменить
                                                    </a>
                                                    <a onclick="getOderInfo('{{$item->id}}')" href="javascript:void(0);" style="font-size: 12px;margin-left: 10px" data-toggle="modal" data-target="#oderInfoModal">
                                                        <i style="margin-right: 5px;" class="fa fa-print" aria-hidden="true"></i>Сформировать товарный чек
                                                    </a>
                                                @endif
                                            </td>
                                            <td class="text-right">&#8372;
                                            @php
                                                $sum = 0;
                                                foreach ($item->cartProduct as $product){
                                                    $sum += $product->price * $product->pivot->count;
                                                }
                                                if (isset($item->client->discount)){
                                                    $sum -= round($sum*$item->client->discount->percent/100,2);
                                                }
                                            @endphp
                                                {{(int)$sum}}
                                            </td>
                                            <td style="padding: 12px 0;">
                                                @if($item->oder_status !== 5)
                                                    <div style="width: 90%;" class="rs-select2--dark rs-select2--md m-r-10 rs-select2--border">
                                                        <select class="js-select2" name="order_status_code" onchange="orderStatus('{{$item->id}}',this)">
                                                            @isset($order_code)
                                                                @foreach($order_code as $v)
                                                                    @if($v->id !== 5)
                                                                        <option @if($v->id === $item->oder_status) selected @endif value="{{$v->id}}">{{$v->name}}</option>
                                                                    @endif
                                                                @endforeach
                                                            @endisset
                                                        </select>
                                                        <div class="dropDownSelect2"></div>
                                                    </div>
                                                @else
                                                    <span class="small text-warning">(отменён)</span>
                                                @endif
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

    @include('admin.orders.partrials.pgf_form')

    <script>
        function backOrder(id) {
            const confirm_var = confirm('Отменить заказа?Есле заказ был оплачен то это повлечет возврат денег на счёт профиля');
            if (confirm_var) {
                $.get(`{{route('admin.product.change_status_order')}}?orderID=${id}&statusID=5`,function (data) {
                    alert(data.response);
                });
            }
        }
    </script>
@endsection
