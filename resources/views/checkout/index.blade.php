@extends('layouts.app')

@section('content')

    <!-- Content -->
    <div id="content">

        <!-- Linking -->
        @component('component.breadcrumb',[
            'links' => [
                (object)['title' => 'Оформление заказа']
            ]
        ])
        @endcomponent

        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <section class="shopping-cart padding-bottom-30">
                        <div class="table-responsive" id="checkout-cart-block">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Товар</th>
                                    <th class="text-center">Цена</th>
                                    <th class="text-center">Количество</th>
                                    <th class="text-center">Общая цена</th>
                                    <th>&nbsp; </th>
                                </tr>
                                </thead>
                                <tbody>
                                <!-- Item Cart -->
                                @isset($products)
                                    @forelse($products as $product)
                                        <tr id="tr_product{{$product->id}}">
                                            <td><div class="media">
                                                    <div class="media-left"> <a href="{{route('product',$product->alias)}}"> <img class="img-responsive" src="{{asset('images/item-img-1-1.jpg')}}" alt="{{$product->name}}" > </a> </div>
                                                </div></td>
                                            <td class="text-center padding-top-60">{{$product->price}} грн</td>
                                            <td class="text-center"><!-- Quinty -->

                                                <div class="quinty padding-top-20">
                                                    <input id="count{{$product->id}}" type="number" value="{{$product->count}}" oninput="changeCount({{$product->id}},{{$product->cart_id}},'{{route('product_count')}}')">
                                                </div></td>
                                            <td class="text-center padding-top-60" id="price{{$product->id}}">{{((double)$product->price * (integer)$product->count)}} грн</td>
                                            <td class="text-center padding-top-60"><a href="#." class="remove" onclick="deleteProduct({{$product->id}},{{$product->cart_id}},'{{route('product_delete')}}'); return false;"><i class="fa fa-close"></i></a></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">
                                                <div class="alert alert-info margin-15" role="alert">
                                                    Похоже вы еще не добавляли товары в корзину, <strong>начните прямо сейчас</strong>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                @endisset
                                </tbody>
                            </table>

                            <!-- Promotion -->
                            <div class="promo">
                                <div class="coupen">
                                    <label> Promotion Code
                                        <input type="text" placeholder="Your code here">
                                        <button type="submit"><i class="fa fa-arrow-circle-right"></i></button>
                                    </label>
                                </div>

                                <!-- Grand total -->
                                <div class="g-totel">
                                    <h5>{{__('Общая сумма: ')}}
                                        <span id="total-price-checkout">
                                        @php
                                            $sum = 0.00;
                                            if (isset($products)){
                                                foreach ($products as $product){
                                                    $sum += (double)$product->price * (integer)$product->count;
                                                }
                                            }
                                        @endphp
                                            {{$sum}} грн
                                </span>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="col-md-4">
                @guest
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#new_user" aria-controls="home" role="tab" data-toggle="tab">
                                {{__('Я новый покупатель')}}
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#old_user" aria-controls="profile" role="tab" data-toggle="tab">
                                {{__('Я постоянный клиент')}}
                            </a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="login-sec padding-top-30 tab-content">
                        <div role="tabpanel" class="tab-pane active" id="new_user">
                            <form type="POST" action="{{route('checkout.new_user')}}">
                                @csrf
                                <ul class="row">
                                    <li class="col-sm-12">
                                        <label>{{ __('Имя') }}
                                            <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>
                                        </label>
                                        @if ($errors->has('name'))
                                            <span class="invalid-feedback">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                        @endif
                                    </li>
                                    <li class="col-sm-12">
                                        <label>{{__('Фамилия')}}
                                            <input type="text" class="form-control {{ $errors->has('sername') ? ' is-invalid' : '' }}" name="sername" value="{{ old('sername') }}" required>
                                        </label>
                                        @if ($errors->has('sername'))
                                            <span class="invalid-feedback">
                                            <strong>{{ $errors->first('sername') }}</strong>
                                        </span>
                                        @endif
                                    </li>
                                    <li class="col-sm-12">
                                        <label>{{__('Отчество')}}
                                            <input type="text" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name" value="{{ old('last_name') }}" required>
                                        </label>
                                        @if ($errors->has('last_name'))
                                            <span class="invalid-feedback">
                                            <strong>{{ $errors->first('last_name') }}</strong>
                                        </span>
                                        @endif
                                    </li>
                                    <li class="col-sm-12">
                                        <label>{{__('Адрес електронной почты')}}
                                            <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
                                        </label>
                                        @if ($errors->has('email'))
                                            <span class="invalid-feedback">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                        @endif
                                    </li>
                                    <li class="col-sm-12">
                                        <label>{{__('Телефон')}}
                                            <input type="tel" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="{{ old('phone') }}" required>
                                        </label>
                                        @if ($errors->has('phone'))
                                            <span class="invalid-feedback">
                                            <strong>{{ $errors->first('phone') }}</strong>
                                        </span>
                                        @endif
                                    </li>
                                    <li class="col-sm-12">
                                        <label class="relative country">{{__('Страна')}}
                                            <input id="country" oninput="getCountry($(this))" type="text" class="form-control{{ $errors->has('country') ? ' is-invalid' : '' }}" name="country" value="{{ old('country') }}" required autocomplete="off">
                                            <span class="loader"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></span>
                                        </label>
                                        @if ($errors->has('country'))
                                            <span class="invalid-feedback">
                                            <strong>{{ $errors->first('country') }}</strong>
                                        </span>
                                        @endif
                                    </li>
                                    <li class="col-sm-12">
                                        <label class="relative city">{{__('Город')}}
                                            <input id="city" oninput="getCity($(this))" type="text" class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" value="{{ old('city') }}" required autocomplete="off">
                                            <span class="loader"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></span>
                                        </label>
                                        @if ($errors->has('city'))
                                            <span class="invalid-feedback">
                                            <strong>{{ $errors->first('city') }}</strong>
                                        </span>
                                        @endif
                                    </li>
                                    <li class="col-sm-12">
                                        <label>{{__('Доставка *')}}
                                            <select id="delivery-service" name="delivery_service" class="form-control" required>
                                                <option value="novaposhta" selected>{{__('Новая почта')}}</option>
                                                <option value="samovivoz">{{__('Самовывоз')}}</option>
                                            </select>
                                        </label>
                                    </li>
                                    <li class="col-sm-12">
                                        <label class="relative delivery-department">{{__('Отделение  *')}}
                                            <input id="delivery_department" type="text" class="form-control{{ $errors->has('delivery_department') ? ' is-invalid' : '' }}" name="delivery_department" autocomplete="off">
                                            <span class="loader"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></span>
                                        </label>
                                        @if ($errors->has('delivery_department'))
                                            <span class="invalid-feedback">
                                            <strong>{{ $errors->first('delivery_department') }}</strong>
                                        </span>
                                        @endif
                                    </li>
                                    <li class="col-sm-12 padding-bottom-10 padding-top-10">
                                        <script src="{{asset('js/map.js')}}"></script>
                                        <div id="map" style="height: 330px;"></div>
                                        <script type="text/javascript" async defer
                                                src="https://maps.googleapis.com/maps/api/js?v=3&libraries=places&callback=initMap&key={{config('app.google_key')}}"></script>
                                    </li>
                                    <li class="col-sm-12">
                                        <label>{{__('Пароль')}}

                                            <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                                        </label>
                                        @if ($errors->has('password *'))
                                            <span class="invalid-feedback">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                        @endif
                                    </li>
                                    <li class="col-sm-12">
                                        <label>{{__('Подтвердите пароль *')}}
                                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                        </label>
                                    </li>
                                    <li class="col-sm-12 text-left">
                                        <button type="submit" class="btn-round">{{__('Регистрация')}}</button>
                                    </li>
                                </ul>
                            </form>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="old_user">
                            <form method="POST" action="{{ route('checkout.old_user') }}">
                                @csrf

                                <ul class="row">
                                    <li class="col-sm-12">
                                        <label>{{__('E-mail')}}
                                            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
                                        </label>
                                        @if ($errors->has('email'))
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                        @endif
                                    </li>
                                    <li class="col-sm-12">
                                        <label>{{__('Пароль')}}
                                            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                                            @if ($errors->has('password'))
                                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                            @endif
                                        </label>
                                    </li>
                                    <li class="col-sm-6">
                                        <div class="checkbox checkbox-primary">
                                            <input id="cate1" class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <label for="cate1"> {{__('Запомнить меня')}} </label>
                                        </div>
                                    </li>
                                    <li class="col-sm-6"> <a href="{{route('password.request')}}" class="forget">{{__('Забыли пароль?')}}</a> </li>
                                    <li class="col-sm-12 text-left">
                                        <button type="submit" class="btn-round">{{__('Войти')}}</button>
                                    </li>
                                </ul>
                            </form>
                        </div>
                    </div>
                @else
                     <div class="login-sec">
                         @php
                             $delivery_inf = DB::table('delivery_info')
                             ->where('user_id',Auth::user()->id)
                             ->join('country','country.id','=','delivery_info.delivery_country')
                             ->join('city','city.id','=','delivery_info.delivery_city')
                             ->select('delivery_info.*','country.name as country','city.name as city')
                             ->first();
                         @endphp
                         <form method="POST" action="{{ route('checkout.create_oder') }}">
                             @csrf

                             <ul class="row">
                                 <li class="col-sm-12">
                                     <label>{{ __('Имя') }}
                                         <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ Auth::user()->name }}" required autofocus>
                                     </label>
                                     @if ($errors->has('name'))
                                         <span class="invalid-feedback">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                     @endif
                                 </li>
                                 <li class="col-sm-12">
                                     <label>{{__('Фамилия')}}
                                         <input type="text" class="form-control {{ $errors->has('sername') ? ' is-invalid' : '' }}" name="sername" value="{{ Auth::user()->sername }}" required>
                                     </label>
                                     @if ($errors->has('sername'))
                                         <span class="invalid-feedback">
                                            <strong>{{ $errors->first('sername') }}</strong>
                                        </span>
                                     @endif
                                 </li>
                                 <li class="col-sm-12">
                                     <label>{{__('Отчество')}}
                                         <input type="text" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name" value="{{ Auth::user()->last_name }}" required>
                                     </label>
                                     @if ($errors->has('last_name'))
                                         <span class="invalid-feedback">
                                            <strong>{{ $errors->first('last_name') }}</strong>
                                        </span>
                                     @endif
                                 </li>
                                 <li class="col-sm-12">
                                     <label>{{__('Адрес електронной почты')}}
                                         <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ Auth::user()->email  }}" required>
                                     </label>
                                     @if ($errors->has('email'))
                                         <span class="invalid-feedback">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                     @endif
                                 </li>
                                 <li class="col-sm-12">
                                     <label>{{__('Телефон')}}
                                         <input type="tel" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="{{ isset($delivery_inf) ? $delivery_inf->phone : '' }}" required>
                                     </label>
                                     @if ($errors->has('phone'))
                                         <span class="invalid-feedback">
                                            <strong>{{ $errors->first('phone') }}</strong>
                                        </span>
                                     @endif
                                 </li>
                                 <li class="col-sm-12">
                                     <label class="relative country">{{__('Страна')}}
                                         <input id="country" oninput="getCountry($(this))" type="text" class="form-control{{ $errors->has('country') ? ' is-invalid' : '' }}" name="country" value="{{ isset($delivery_inf) ? $delivery_inf->country : '' }}" required autocomplete="off">
                                         <span class="loader"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></span>
                                     </label>
                                     @if ($errors->has('country'))
                                         <span class="invalid-feedback">
                                            <strong>{{ $errors->first('country') }}</strong>
                                        </span>
                                     @endif
                                 </li>
                                 <li class="col-sm-12">
                                     <label class="relative city">{{__('Город')}}
                                         <input id="city" oninput="getCity($(this))" type="text" class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" value="{{ isset($delivery_inf) ? $delivery_inf->city : '' }}" required autocomplete="off">
                                         <span class="loader"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></span>
                                     </label>
                                     @if ($errors->has('city'))
                                         <span class="invalid-feedback">
                                            <strong>{{ $errors->first('city') }}</strong>
                                        </span>
                                     @endif
                                 </li>
                                 <li class="col-sm-12">
                                     <label>{{__('Доставка *')}}
                                         <select id="delivery-service" name="delivery_service" class="form-control" required>
                                             <option value="novaposhta" @if(isset($delivery_inf)) @if($delivery_inf->delivery_service === 'novaposhta') selected @endif @else selected @endif>{{__('Новая почта')}}</option>
                                             <option value="samovivoz" @isset($delivery_inf) @if($delivery_inf->delivery_service === 'samovivoz') selected @endif @endisset>{{__('Самовывоз')}}</option>
                                         </select>
                                     </label>
                                 </li>
                                 <li class="col-sm-12">
                                     <label class="relative delivery-department">{{__('Отделение  *')}}
                                         <input value="@isset($delivery_inf) {{$delivery_inf->delivery_department}} @endisset" id="delivery_department" type="text" class="form-control{{ $errors->has('delivery_department') ? ' is-invalid' : '' }}" name="delivery_department" autocomplete="off">
                                         <span class="loader"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></span>
                                     </label>
                                     @if ($errors->has('delivery_department'))
                                         <span class="invalid-feedback">
                                            <strong>{{ $errors->first('delivery_department') }}</strong>
                                        </span>
                                     @endif
                                 </li>
                                 <li class="col-sm-12">
                                    <script src="{{asset('js/map.js')}}"></script>
                                    <div id="map" style="height: 330px;"></div>
                                    <script type="text/javascript" async defer
                                         src="https://maps.googleapis.com/maps/api/js?v=3&libraries=places&callback=initMap&key={{config('app.google_key')}}"></script>
                                 </li>
                                 <li class="col-sm-12 text-left">
                                     <button type="submit" class="btn-round">{{__('Сформировать заказ')}}</button>
                                 </li>
                             </ul>
                         </form>
                     </div>
                @endguest
                </div>
            </div>
        </div>


    </div>
    <!-- End Content -->
    <script>
        $(document).ready(function () {
            $('#delivery_department').on('focus input',function () {
                const flag = ($('#delivery-service').val() === 'novaposhta');
                if (flag){
                    const city = $('#city').val();
                    $('.delivery-department .loader').css({display: 'inline-block'});
                    $('#delivery_department').autocomplete({
                        source: (request, response) => {
                            $.ajax({
                                url: 'https://api.novaposhta.ua/v2.0/json/',
                                method: "POST",
                                data:JSON.stringify({
                                    "apiKey": "{{config('app.novaposhta_key')}}",
                                    "modelName": "AddressGeneral",
                                    "calledMethod": "getWarehouses",
                                    "methodProperties": {
                                        "Language": "ru",
                                        "CityName": `${city}`,
                                    }
                                }),
                                success: (data) => {
                                    $('.delivery-department .loader').css({display: 'none'});
                                    response($.map(data.data, (item) => {
                                        return{
                                            value: item.DescriptionRu,
                                        }
                                    }));
                                }
                            });
                        },
                        minLength:0
                    }).on('focus', function() { $(this).keydown(); });
                }
            });

            $('#new_user form').submit(function (e) {
                e.preventDefault();
               $.post($(this).attr('action'),$(this).serialize(),function (data) {
                   if (data.errors !== undefined){
                       let errors_html =  ``;
                       for (let key in data.errors){
                           errors_html += `${data.errors[key][0]}\n`;
                       }
                       alert(errors_html);
                   }
                   console.log(data)
               });
            });
        });
    </script>

@endsection