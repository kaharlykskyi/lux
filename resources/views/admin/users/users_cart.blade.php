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
            <div class="col-sm-12 m-t-10">
                <h3 class="title-3 m-b-30">{{__('Товары в корзине у заказчиков')}}</h3>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body card-block">
                        <form action="{{route('admin.users_cart')}}" method="get" id="filter_oder">
                            <div class="row form-group">
                                <div class="col col-sm-4">
                                    <div class="row form-group">
                                        <div class="col col-md-3">
                                            <label for="cart_id" class=" form-control-label">cart #:</label>
                                        </div>
                                        <div class="col-12 col-md-9">
                                            <input type="text" id="cart_id" value="{{request()->query('cart_id')}}" name="cart_id" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col col-sm-4">
                                    <div class="row form-group">
                                        <div class="col col-md-3">
                                            <label for="name_product" class=" form-control-label">Название</label>
                                        </div>
                                        <div class="col-12 col-md-9">
                                            <input type="text" id="name_product" value="{{request()->query('name_product')}}" name="name_product" class="form-control">
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
                                                <option value="0">{{__('Не залогиненые')}}</option>
                                                @foreach($clients as $client)
                                                    <option @if((int)request()->query('client_id') === $client->id) selected @endif value="{{$client->id}}">{{$client->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col col-sm-4">
                                    <div class="row form-group">
                                        <div class="col col-md-3">
                                            <label for="date_add_start"  class=" form-control-label">Дата от</label>
                                        </div>
                                        <div class="col-12 col-md-9">
                                            <input type="date" value="{{request()->query('date_add_start')}}" id="date_add_start" name="date_add_start" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col col-sm-4">
                                    <div class="row form-group">
                                        <div class="col col-md-3">
                                            <label for="date_add_end" class=" form-control-label">Дата до</label>
                                        </div>
                                        <div class="col-12 col-md-9">
                                            <input type="date" value="{{request()->query('date_add_end')}}" id="date_add_end" name="date_add_end" class="form-control">
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
                        <button onclick="location.href = '{{route('admin.users_cart')}}'" class="btn btn-danger btn-sm">
                            <i class="fa fa-ban"></i> Отменить
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="table--no-card m-b-30 table-responsive">
                    <table class="table table-borderless table-striped table-earning">
                        <thead>
                        <tr>
                            <th>{{__('cart #')}}</th>
                            <th>{{__('Имя')}}</th>
                            <th>{{__('Код')}}</th>
                            <th>{{__('Клиент')}}</th>
                            <th>{{__('Кол-во')}}</th>
                            <th>{{__('Цена')}}</th>
                            <th>{{__('Дата')}}</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @isset($users_cart_product)
                            @forelse($users_cart_product as $product)
                                <tr>
                                    <td>{{$product->cart->id}}</td>
                                    <td>{{$product->product->name}}</td>
                                    <td>{{$product->product->articles}}</td>
                                    <td class="hover-trigger position-relative">
                                        @if($product->cart->client)
                                            <div class="table-data__info">
                                                <h6>{{$product->cart->client->name}}</h6>
                                            </div>
                                            <div class="hidden hover-show">
                                                <p><strong>ФИО: </strong>{{$product->cart->client->sername . ' '. $product->cart->client->name . ' ' . $product->cart->client->last_name}}</p>
                                                <p><strong>Email: </strong>{{$product->cart->client->email}}</p>
                                                <p><strong>Тип пользователя: </strong>{{$product->cart->client->type_user->name}}</p>
                                                <p><strong>Город: </strong>{{$product->cart->client->userCity->name}}</p>
                                                <p><strong>Адрес: </strong>{{$product->cart->client->deliveryInfo->street . '/' . $product->cart->client->deliveryInfo->house}}</p>
                                                <p><strong>Телефон: </strong>{{$product->cart->client->phone}}</p>
                                            </div>
                                        @else
                                            <div class="table-data__info">
                                                <h6>{{__('Не залогинен')}}</h6>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{$product->count}}</td>
                                    <td>{{$product->product->price}}грн.</td>
                                    <td>{{$product->created_at}}</td>
                                    <td class="font-size-12-440">
                                        <form onsubmit="if(confirm('DELETE?')){return true}else{return false}"
                                              action="{{route('admin.users_cart')}}" method="get">
                                            <input type="hidden" name="delete_product" value="{{$product->id}}">
                                            <button type="submit" class="item" data-toggle="tooltip" data-placement="top" title="{{__('Удалить')}}">
                                                <i class="zmdi zmdi-delete"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9">
                                        <div class="alert alert-warning" role="alert">
                                            {{__('Пользователи ещё не добавляли товары в свои корзины')}}
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
                {{$users_cart_product->links()}}
            </div>
        </div>

        @component('admin.component.footer')@endcomponent
    </div>

@endsection
