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
            @include('admin.component.back')
            <div class="col-12 m-t-10 m-b-10">
                <h3 class="pb-2 display-5">{{__('Заказ №') . $order->id}}</h3>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">{{__('Данные заказа')}}
                            @isset($order->payOder)
                                @if($order->payOder->success_pay === 'true')
                                    <small>
                                        <span class="badge badge-success float-right mt-1">{{__('оплачен')}}</span>
                                    </small>
                                @endif
                            @endisset
                        </strong>
                    </div>
                    <div class="card-body">
                        @include('admin.orders.partrials.client_info')
                    </div>
                </div>
            </div>
            <div class="col-12 m-t-10">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">{{__('Заказаные товары')}}</strong>
                    </div>
                    <div class="card-body">
                        @include('admin.orders.partrials.cart_products')
                    </div>
                </div>
            </div>
            <div class="col-sm-12 justify-content-end">
                <button class="btn btn-success" data-toggle="modal" data-target="#addProductModal">{{__('Добавить товар')}}</button>
            </div>
        </div>

     @component('admin.component.footer')@endcomponent
    </div>

    @include('admin.orders.partrials.add_product')

    <script>
        function stockProductDelivery(id,obg) {
            $.get(`{{route('admin.product.stock')}}?order_id={{$order->id}}&id_product=${id}&id_stock=${$(obg).val()}`,function () {
                alert('Сохранено');
            });
        }

        function deleteProductOrder(id) {
            if(confirm('Удалить?')){
                $.post('{{route('admin.order_edit',request('order'))}}',{_token:'{{csrf_token()}}',delete_product:id},function (data) {
                    $('#order-product-block').html(htmlProduct(data.products));
                    alert(data.mass);
                });
            }
        }

        function htmlProduct(data) {
            let template = '';

            for (let i in data){
                template += `<tr>
                                <td>${data[i].product.name}</td>
                                <td>${data[i].product.articles}</td>
                                <td>
                                    ${data[i].product.price}грн.<br>
                                    <span class="small">${data[i].product.provider_price} ${data[i].product.provider_currency}</span>
                                </td>
                                <td>${data[i].count}</td>
                                <td>
                                    <div class="table-data-feature">
                                        <button onclick="deleteProductOrder('${data[i].id}')" type="button" class="item" data-toggle="tooltip" data-placement="top" title="Удалить">
                                            <i class="zmdi zmdi-delete"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>`;
            }
            return template;
        }
    </script>

@endsection
