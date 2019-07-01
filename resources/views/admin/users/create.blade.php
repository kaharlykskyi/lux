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
            <div class="col-12">
                <div class="card m-t-15">
                    <div class="card-header">
                        <strong class="card-title mb-3">Добавление нового пользователя</strong>
                    </div>
                    <div class="card-body card-block">
                        <form action="{{route('admin.user.create')}}" method="post" novalidate="novalidate">
                            @csrf
                            <div class="form-group">
                                <label for="name" class="control-label mb-1">ФИО</label>
                                <input id="name" type="text" class="form-control {{ $errors->has('fio') ? ' is-invalid' : '' }}" name="fio" value="{{ old('fio') }}" required autofocus>
                                @if ($errors->has('fio'))
                                    <span class="invalid-feedback">
                                         <strong>{{ $errors->first('fio') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="email" class="control-label mb-1">Адрес електронной почты</label>
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="phone" class="control-label mb-1">Телефон</label>
                                <input id="phone" type="tel" class="phone_mask form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="{{ old('phone') }}" required>
                                @if ($errors->has('phone'))
                                    <span class="invalid-feedback">
                                            <strong>{{ $errors->first('phone') }}</strong>
                                        </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="role" class="control-label mb-1">Тип клиента</label>
                                <select id="role" class="form-control" name="role" required>
                                    @isset($roles)
                                        @foreach($roles as $role)
                                            <option value="{{$role->id}}">{{$role->name}}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="password" class="control-label mb-1">Пароль</label>
                                <input id="password" type="text" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                @endif
                            </div>
                            <div>
                                <button id="payment-button" type="submit" class="btn btn-lg btn-info btn-block">Добавить пользователя</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @component('admin.component.footer')@endcomponent
    </div>

@endsection
