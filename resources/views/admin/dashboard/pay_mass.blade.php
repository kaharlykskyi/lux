@extends('admin.layouts.admin')

@section('content')

    <div class="container-fluid m-t-75">
        <div class="row">
            <div class="col-12">
                <h3 class="title-5 m-b-35 m-t-15">{{__('Сообщения об оплате')}}</h3>
                <div class="col-12 m-b-15">
                    <a href="{{route('admin.dashboard')}}" class="btn btn-success">{{__('Назад')}}</a>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body card-block">
                        <form action="{{route('admin.pay_mass')}}" method="get" id="filter_oder">
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
                                            <label for="date_pay_start"  class=" form-control-label">Дата от</label>
                                        </div>
                                        <div class="col-12 col-md-9">
                                            <input type="date" value="{{request()->query('date_pay_start')}}" id="date_pay_start" name="date_pay_start" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col col-sm-4">
                                    <div class="row form-group">
                                        <div class="col col-md-3">
                                            <label for="date_pay_end" class=" form-control-label">Дата до</label>
                                        </div>
                                        <div class="col-12 col-md-9">
                                            <input type="date" value="{{request()->query('date_pay_end')}}" id="date_pay_end" name="date_pay_end" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col col-sm-4">
                                    <div class="row form-group">
                                        <div class="col col-md-3">
                                            <label for="date_price_start"  class=" form-control-label">Сумма от</label>
                                        </div>
                                        <div class="col-12 col-md-9">
                                            <input type="text" value="{{request()->query('date_price_start')}}" id="date_price_start" name="date_price_start" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col col-sm-4">
                                    <div class="row form-group">
                                        <div class="col col-md-3">
                                            <label for="date_price_end" class=" form-control-label">Сумма до</label>
                                        </div>
                                        <div class="col-12 col-md-9">
                                            <input type="text" value="{{request()->query('date_price_end')}}" id="date_price_end" name="date_price_end" class="form-control">
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
                        <button onclick="location.href = '{{route('admin.pay_mass')}}'" class="btn btn-danger btn-sm">
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
                            <th>Заказ</th>
                            <th>Дата оплаты</th>
                            <th>Заказчик</th>
                            <th>Сумма</th>
                        </tr>
                        </thead>
                        <tbody>
                        @isset($pay_mass)
                            @forelse($pay_mass as $item)
                                <tr>
                                    <td>
                                        <a href="{{route('admin.order_edit',$item->cart_id)}}">{{$item->cart_id}}</a>
                                    </td>
                                    <td>{{$item->created_at}}</td>
                                    <td>
                                        {{$item->user->fio}}
                                    </td>
                                    <td>{{(int)$item->price_pay}} грн.</td>
                                </tr>
                            @empty
                                <tr class="tr-shadow">
                                    <td colspan="4">
                                        <div class="alert alert-warning" role="alert">
                                            {{__('Оплат ещё не проводилось')}}
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
                {{$pay_mass->links()}}
            </div>
        </div>
        @component('admin.component.footer')@endcomponent
    </div>

@endsection
