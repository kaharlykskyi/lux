@extends('admin.layouts.admin')

@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container{
            display: block !important;
        }
    </style>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#client_id').select2();
        });
    </script>
@endsection

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
            <div class="col-md-12">
                <h3 class="title-5 m-b-35 m-t-15">{{__('Создание заказа')}}</h3>
                <form action="{{route('admin.order.create')}}" method="post">
                    @csrf
                    <div class="accordion" id="accordionExample">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Новый юзер
                                    </button>
                                </h5>
                            </div>

                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                <div class="card-body">
                                    <div class="row form-group">
                                        <div class="col col-md-3">
                                            <label for="fio" class=" form-control-label">{{__('ФИО')}}</label>
                                        </div>
                                        <div class="col-12 col-md-9">
                                            <input type="text" id="fio" name="fio" class="form-control">
                                            @if ($errors->has('fio'))
                                                <small class="form-text text-danger">{{ $errors->first('fio') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col col-md-3">
                                            <label for="phone" class=" form-control-label">{{__('Телефон')}}</label>
                                        </div>
                                        <div class="col-12 col-md-9">
                                            <input type="text" id="phone" name="phone" class="form-control">
                                            @if ($errors->has('phone'))
                                                <small class="form-text text-danger">{{ $errors->first('phone') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                                <h5 class="mb-0">
                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Уже существующий
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                <div class="card-body">
                                    <div class="row form-group">
                                        <div class="col col-md-3">
                                            <label for="client_id" class=" form-control-label">Заказчик</label>
                                        </div>
                                        <div class="col-12 col-md-9">
                                            <select name="client_id" id="client_id" class="form-control">
                                                <option selected value="0"></option>
                                                @foreach($users as $user)
                                                    <option value="{{$user->id}}">{{$user->fio}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
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
                        <div class="col-sm-12 text-right">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addProductModal">{{__('Добавить товар')}}</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fa fa-dot-circle-o"></i> {{__('Сохранить')}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @component('admin.component.footer')@endcomponent
    </div>

    @include('admin.orders.partrials.add_product')

    <script>
        function addProduct(id) {
            const count = $(`#prod_${id}`).val();
            const article = $(`#product_article_${id}`).text();
            const name = $(`#product_name_${id}`).text();
            const price = $(`#product_price_${id}`).text();
            $('#order-product-block').append(`
                <tr id="product_row_${id}">
                                <td>
                                    ${name}
                                    <input type="hidden" name="product_id[]" value="${id}">
                                </td>
                                <td>${article}</td>
                                <td>${price}грн.<br></td>
                                <td><input type="number" name="count_product[]" value="${count}"></td>
                                <td>
                                    <div class="table-data-feature">
                                        <button onclick="deleteProductOrder('${id}')" type="button" class="item" data-toggle="tooltip" data-placement="top" title="Удалить">
                                            <i class="zmdi zmdi-delete"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
            `);
            console.log([count,article,name,price])
        }

        function deleteProductOrder(id) {
            $('#product_row_' + id).remove();
        }
    </script>

@endsection
