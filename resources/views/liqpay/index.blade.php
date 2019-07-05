@extends('layouts.app')

@section('content')

    <div id="content">
        <!-- Linking -->
        @component('component.breadcrumb',[
            'links' => [
                (object)['title' => 'пополнение баланса']
            ]
        ])
        @endcomponent

        <section class="contact-sec padding-top-40 padding-bottom-80">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-offset-3 col-sm-6">
                        <form action="{{route('liqpay.pay')}}" method="post" class="login-sec">
                            @csrf
                            @if(request()->has('order'))
                                <input type="hidden" name="order_id" value="{{request('order')}}">
                            @endif
                            <div class="form-group">
                                <label for="amount">
                                    @if($sum > 0)
                                        {{__('Стоимость заказа')}}
                                    @else
                                        {{__('На какую сумму пополнить')}}
                                    @endif
                                </label>
                                @php
                                    if (isset($user->balance->balance)){
                                        $sum = $sum - number_format((float)$user->balance->balance, 2, '.', '');
                                    }
                                @endphp
                                <input type="text" @if($sum > 0) readonly value="{{$sum}}" @endif class="form-control" name="amount" id="amount" placeholder="{{__('Введите число')}}" required>
                                @if ($errors->has('amount'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('amount') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-round">{{__('Оплатить')}}</button>
                        </form>
                    </div>
                </div>
            </div>

        </section>
    </div>


@endsection