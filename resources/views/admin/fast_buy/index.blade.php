@extends('admin.layouts.admin')

@section('content')

    <div class="container-fluid m-t-75" style="height: 100vh;">
        @if (session('status'))
            <div class="row">
                <div class="col-sm-12">
                    <div class="alert alert-info" role="alert">
                        {{ session('status') }}
                    </div>
                </div>
            </div>
        @endif
        <div class="row p-t-25">
            <div class="col-12">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link @if(URL::current() === route('admin.fast_buy','new')) active @endif" href="{{route('admin.fast_buy','new')}}">{{__('Новые запросы')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(URL::current() === route('admin.fast_buy','old')) active @endif" href="{{route('admin.fast_buy','old')}}">{{__('Старые запросы')}}</a>
                    </li>
                </ul>
                <!-- DATA TABLE-->
                <div class="table-responsive m-b-40 m-t-30">
                    <table class="table table-borderless table-data3" id="stock_product" style="display: none;">
                        <thead>
                        <tr>
                            <th>Склад</th>
                            <th>Компания</th>
                            <th>Остатки</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <table class="table table-borderless table-data3">
                        <thead>
                        <tr>
                            <th>{{__('Телефон')}}</th>
                            <th>{{__('Дата')}}</th>
                            <th>{{__('Продукт')}}</th>
                            <th>{{__('Артикль')}}</th>
                            <th>{{__('Обработан')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                            @forelse($fast_buy as $item)
                                <tr>
                                    <td>{{$item->phone}}</td>
                                    <td>{{$item->created_at}}</td>
                                    <td>
                                        <span class="m-r-5" onclick="getStockProductInfo('{{$item->id}}',this)" style="cursor: pointer"><i class="fa fa-info" aria-hidden="true"></i></span>
                                        {{$item->product->name}}
                                    </td>
                                    <td>{{$item->product->articles}}</td>
                                    <td>
                                        <input type="checkbox" onchange="statusFusBuy('{{$item->id}}',this)" @if($item->status === 1) checked @endif>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="alert text-center alert-info" role="alert">
                                            {{__('Ещё нету заявок на быструю покупку')}}
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <script>
                        function statusFusBuy(id,obj) {
                            $.get(`{{route('admin.fast_buy','new')}}?fust_buy=${id}&data=${$(obj).val()}`,function (data) {
                                alert(data.response);
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
                            $('#stock_product').css({
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
                                        </tr>`;
                                });
                                $('#stock_product tbody').html(data_str);
                            });
                        }
                    </script>
                </div>
                <!-- END DATA TABLE-->
            </div>
            <div class="col-12">
                {{$fast_buy->links()}}
            </div>
        </div>
        @component('admin.component.footer')@endcomponent
    </div>

@endsection