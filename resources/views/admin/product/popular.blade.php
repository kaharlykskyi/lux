@extends('admin.layouts.admin')

@section('content')

    <div class="container-fluid m-t-75">
        <div class="row">
            <div class="col-12">
                <h3 class="title-5 m-b-35 m-t-15">{{__('Популярные товары')}}</h3>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body card-block">
                        <form action="{{route('admin.product.popular')}}" method="get" id="filter_product">
                            <div class="row form-group">
                                <div class="col col-sm-4">
                                    <div class="row form-group">
                                        <div class="col col-md-3">
                                            <label for="supplier" class=" form-control-label">Производитель</label>
                                        </div>
                                        <div class="col-12 col-md-9">
                                            <input type="text" id="supplier" value="{{request()->query('supplier')}}" name="supplier" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col col-sm-4">
                                    <div class="row form-group">
                                        <div class="col col-md-3">
                                            <label for="article"  class=" form-control-label">Код</label>
                                        </div>
                                        <div class="col-12 col-md-9">
                                            <input type="text" value="{{request()->query('article')}}" id="article" name="article" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col col-sm-4">
                                    <div class="row form-group">
                                        <div class="col col-md-3">
                                            <label for="name" class=" form-control-label">Название</label>
                                        </div>
                                        <div class="col-12 col-md-9">
                                            <input type="text" value="{{request()->query('name')}}" id="name" name="name" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <button onclick="$('#filter_product').submit();" class="btn btn-primary btn-sm">
                            <i class="fa fa-dot-circle-o"></i> Фильтровать
                        </button>
                        <button onclick="location.href = '{{route('admin.product.popular')}}'" class="btn btn-danger btn-sm">
                            <i class="fa fa-ban"></i> Отменить
                        </button>
                    </div>
                </div>
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
                                    <td>{{$product->brand}}</td>
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
