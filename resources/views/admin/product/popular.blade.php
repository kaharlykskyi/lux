@extends('admin.layouts.admin')

@section('content')

    <div class="container-fluid m-t-75">
        <div class="row">
            <div class="col-12">
                <h3 class="title-5 m-b-35 m-t-15">{{__('Популярные товары')}}</h3>
            </div>
            <div class="col-12">
                @component('admin.product.partrials.filter',['link' => route('admin.product.popular'),'providers' => $providers]) @endcomponent
            </div>
            <div class="col-md-12">
                <div class="table-responsive table--no-card m-b-30">
                    <table class="table table-borderless table-striped table-earning">
                        <thead>
                        <tr>
                            <th>Артикул</th>
                            <th>Наименование</th>
                            <th>Бренд</th>
                            <th class="text-right">Количество</th>
                        </tr>
                        </thead>
                        <tbody>
                        @isset($popular_products)
                            @forelse($popular_products as $product)
                                <tr>
                                    <td>{{$product->articles}}</td>
                                    <td>{{$product->name}}</td>
                                    <td>
                                        @if($product->original === 1)
                                            @foreach($manufacturers as $item)
                                                @if($item->id === $product->brand)
                                                    {{$item->matchcode}}
                                                @endif
                                            @endforeach
                                        @else
                                            @foreach($suppliers as $item)
                                                @if($item->id === $product->brand)
                                                    {{$item->matchcode}}
                                                @endif
                                            @endforeach
                                        @endif
                                    </td>
                                    <td class="text-right">{{$product->count_bay}}</td>
                                </tr>
                            @empty
                                <tr class="tr-shadow">
                                    <td colspan="5">
                                        <div class="alert alert-warning" role="alert">
                                            {{__('Товаров ещё нету')}}
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        @endisset

                        </tbody>
                    </table>
                </div>
                <!-- END DATA TABLE -->
            </div>
            <div class="col-sm-12">
                {{$popular_products->links()}}
            </div>
        </div>
    @component('admin.component.footer')@endcomponent

    </div>

@endsection
