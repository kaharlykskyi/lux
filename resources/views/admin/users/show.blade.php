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
            <div class="col-sm-12">
                <!-- USER DATA-->
                <div class="user-data m-b-30">
                    <h3 class="title-3 m-b-30">
                        <i class="zmdi zmdi-account-calendar"></i>{{__('Информация о пользователе - ' . $user->name)}}</h3>
                    <div class="col-12 m-b-15">
                        <a href="{{route('admin.users')}}" class="btn btn-success">{{__('Назад')}}</a>
                    </div>
                    <div class="au-card au-card--bg-blue au-card-top-countries m-b-30 m-l-10 m-r-10">
                        <div class="au-card-inner">
                            <div class="table-responsive">
                                <table class="table table-top-countries">
                                    <tbody>
                                    <tr>
                                        <td>{{__('Фамилия')}}</td>
                                        <td class="text-right">{{$user->sername}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{__('Имя')}}</td>
                                        <td class="text-right">{{$user->name}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{__('Отчество')}}</td>
                                        <td class="text-right">{{$user->last_name}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{__('E-mail')}}</td>
                                        <td class="text-right">{{$user->email}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{__('Страна')}}</td>
                                        <td class="text-right">{{$user->country}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{__('Город')}}</td>
                                        <td class="text-right">{{$user->city}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{__('Телефон')}}</td>
                                        <td class="text-right">{{$user->phone}}
                                            @isset($dop_phone) @foreach($dop_phone as $val) {{__(', ' . $val->phone)}} @endforeach @endisset</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END USER DATA-->
            </div>
            <div class="col-sm-12 m-t-15 text-right">
                <button data-toggle="modal" data-target="#setBalance" class="btn btn-success">{{__('Пополнить баланс')}}</button>
            </div>
            <div class="col-sm-12 m-t-15">
                <ul class="list-group">
                    <li class="list-group-item">
                        <p class="h5">Баланс: <strong>@if(isset($balance)){{floatval($balance->balance)}}@else{{__('0.00')}}@endif</strong> грн</p>
                    </li>
                </ul>
            </div>
            <div class="col-sm-12">
                <table class="table table-borderless table-striped table-earning">
                    <thead>
                    <tr>
                        <th>{{__('ID')}}</th>
                        <th>{{__('Дата')}}</th>
                        <th>{{__('Сумма')}}</th>
                        <th>{{__('Статус')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($balance_history as $item)
                        <tr>
                            <td>{{$item->id}}</td>
                            <td>{{$item->created_at}}</td>
                            <td>{{$item->balance_refill}}</td>
                            <td>{{($item->status === 1)?__('успешно'):__('отказ')}}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="alert alert-info margin-15" role="alert">
                                    {{__('Платежи ещё не производились')}}
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="col-sm-12 m-t-15">
                <h3 class="pb-2 display-5">{{__('Взаиморасчёты')}}</h3>
                <table class="table table-borderless table-striped table-earning">
                    <thead>
                    <tr>
                        <th>{{__('ID')}}</th>
                        <th>{{__('Дата')}}</th>
                        <th>{{__('Сумма')}}</th>
                        <th>{{__('Остаток')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($mutual_settelement as $item)
                        <tr>
                            <td>{{$item->id}}</td>
                            <td>{{$item->created_at}}</td>
                            <td>{{$item->change}}</td>
                            <td>{{$item->balance}}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="alert alert-info margin-15" role="alert">
                                    {{__('Взаиморазчётов ещё не производилось')}}
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @component('admin.component.footer')@endcomponent
    </div>

    <!-- modal medium -->
    <div class="modal fade" id="setBalance" tabindex="-1" role="dialog" aria-labelledby="setBalanceLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mediumModalLabel">{{__('Изменение баланса - ' . $user->name)}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('admin.user.change_balance')}}" method="post" id="balanceForm" class="form-horizontal">
                        @csrf
                        <input type="hidden" name="user_id" value="{{$user->id}}">
                        <div class="row form-group">
                            <div class="col col-md-3">
                                <label class=" form-control-label">{{__('Имя')}}</label>
                            </div>
                            <div class="col-12 col-md-9">
                                <p class="form-control-static">{{$user->name}}</p>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col col-md-3">
                                <label for="textarea-input" class=" form-control-label">{{__('Описание')}}</label>
                            </div>
                            <div class="col-12 col-md-9">
                                <textarea name="description" id="textarea-input" rows="6" placeholder="Content..." class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col col-md-3">
                                <label for="select" class=" form-control-label">{{__('Валюта')}}</label>
                            </div>
                            <div class="col-12 col-md-9">
                                <select name="currency" id="select" class="form-control">
                                    <option value="UAH">UAH</option>
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col col-md-3">
                                <label for="type_operation" class=" form-control-label">{{__('Тип операции')}}</label>
                            </div>
                            <div class="col-12 col-md-9">
                                <select name="type_operation" id="type_operation" class="form-control">
                                    <option selected value="1">{{__('custom.type_operation_balance.1')}}</option>
                                    <option value="2">{{__('custom.type_operation_balance.2')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col col-md-3">
                                <label for="change" class=" form-control-label">{{__('Сумма')}}</label>
                            </div>
                            <div class="col-12 col-md-9">
                                <input type="text" id="change" name="change" placeholder="100" class="form-control" required>
                                <small class="form-text text-muted">{{__('для списание введите отрицательное значение')}}</small>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Отмена')}}</button>
                    <button onclick="$('#balanceForm').submit();" type="button" class="btn btn-primary">{{__('Пополнить')}}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal medium -->


@endsection
