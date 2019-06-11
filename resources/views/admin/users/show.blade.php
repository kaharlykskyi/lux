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
                                <form action="{{route('admin.user.show',$user->id)}}" method="post" class="form-horizontal">
                                    @csrf
                                    @include('admin.users.partrials.user_info')
                                </form>
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
                        <p class="h5">Баланс: <strong>@if(isset($balance)){{(int)$balance->balance}}@else{{__('0.00')}}@endif</strong> грн</p>
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
                            <td>{{(int)$item->balance_refill}}</td>
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
                            <td>{{(int)$item->change}}</td>
                            <td>{{(int)$item->balance}}</td>
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

    @include('admin.users.partrials.change_balance_modal')


@endsection
