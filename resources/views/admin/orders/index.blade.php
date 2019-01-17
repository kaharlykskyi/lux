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
                        <div class="table-responsive table--no-card m-b-30">
                            <table class="table table-borderless table-striped table-earning">
                                <thead>
                                <tr>
                                    <th>Дата</th>
                                    <th>ID заказа</th>
                                    <th>клиент</th>
                                    <th class="text-right">Общяя цена</th>
                                    <th class="text-right">Статус заказа</th>
                                    <th class="text-right">Накладная</th>
                                </tr>
                                </thead>
                                <tbody>
                                @isset($paginatedItems)
                                    @forelse($paginatedItems as $item)
                                        <tr>
                                            <td>
                                                <span class="m-r-10">
                                                    <i data-toggle="modal" data-target="#orderInfo" onclick="getOrderInfo({{$item->id}})" class="fa fa-info" style="cursor: pointer"></i>
                                                </span>
                                                {{$item->updated_at}}
                                            </td>
                                            <td>{{$item->id}}</td>
                                            <td>{{$item->name}}</td>
                                            <td class="text-right">&#8372; {{$item->total_price}}</td>
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
                                            <td>
                                                <div class="form-group">
                                                    <input onblur="saveInvoice('{{$item->id}}',this)" name="invoice_np" type="text" class="form-control" aria-required="true" aria-invalid="false" value="{{$item->invoice_np}}">
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
                        {{$paginatedItems->links()}}
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
                                    <td class="identification-wrapper">
                                        <span class="m-r-10">
                                            <i onclick="getStockProductInfo(${item.id},this)" class="fa fa-info" style="cursor: pointer" title="Остатки на складе"></i>
                                        </span>
                                        ${item.id}
                                    </td>
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

        function getStockProductInfo(id,obj) {
            $('#stock_product tbody').html(`<tr>
                                                <td colspan="3">
                                                    <p class="text-center">
                                                        <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                                                        <span class="sr-only">Loading...</span>
                                                    </p>
                                                </td>
                                            </tr>`);
            console.log($(obj).position());
            const y = $(obj).position().left;
            const x = $(obj).position().top;
            $('#stock_product').removeClass('hidden').css({
                position: 'absolute',
                top: x,
                left: y,
                display: 'block',
                zIndex: 10000
            });

            $.get(`{{route('admin.product.info_product_stock')}}?productID=${id}`,function (data) {
                let data_str = '';
                data.response.forEach(function (item) {
                    data_str += `
                                <tr>
                                    <td>${item.name}</td>
                                    <td>${item.company}</td>
                                    <td>${item.count}</td>
                                </tr>
                    `;
                });
                $('#stock_product tbody').html(data_str);
            });
        }

        function orderStatus(id,obj) {
            $.get(`{{route('admin.product.change_status_order')}}?orderID=${id}&statusID=${$(obj).val()}`,function (data) {
                alert(data.response);
            });
        }

        function saveInvoice(id,obj) {
            if ($(obj).val() !== ''){
                $.get(`{{route('admin.product.change_status_order')}}?orderID=${id}&invoice=${$(obj).val()}`,function (data) {
                    alert(data.response);
                });
            }
        }
    </script>

@endsection