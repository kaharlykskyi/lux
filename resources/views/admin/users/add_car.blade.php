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
                <div class="user-data">
                    <h3 class="title-3 m-b-30">
                        <i class="fa fa-car" aria-hidden="true"></i>{{__('Гараж пользователя - ' . $user->fio)}}</h3>
                    @include('admin.component.back')
                </div>
            </div>
            <div class="col-sm-12 m-t-15">
                <div class="card">
                    <div class="card-header">
                        <strong>{{__('Добавления ')}}</strong> {{__('автомобиля в гараж')}}
                    </div>
                    <div class="card-body card-block">
                        <form action="{{route('admin.user.garage.add',$user->id)}}" method="post" class="form-horizontal">
                            @csrf

                            @include('admin.users.partrials.add_car_form')
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @component('admin.component.footer')@endcomponent
    </div>

@endsection
