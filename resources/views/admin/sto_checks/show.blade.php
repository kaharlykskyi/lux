@extends('admin.layouts.admin')

@section('content')

    <div class="container-fluid m-t-75">
        <div class="row">
            <div class="col-6 m-t-10">
                <h4 style="font-weight: 600;margin-bottom: 5px">{{$decode_company_data->company_name}}</h4>
                <ul class="list-group" style="border: none;">
                    <li style="border: none;padding: 5px 15px;" class="list-group-item">
                        Адреса:{{$decode_company_data->company_address}}
                    </li>
                    <li style="border: none;padding: 5px 15px;" class="list-group-item">
                        Тел: {{$decode_company_data->company_tel}}
                    </li>
                    <li style="border: none;padding: 5px 15px;" class="list-group-item">
                        Код: {{$decode_company_data->company_code}}
                    </li>
                    <li style="border: none;padding: 5px 15px;" class="list-group-item">
                        Банк: {{$decode_company_data->company_bank}}
                    </li>
                    <li style="border: none;padding: 5px 15px;" class="list-group-item">
                        МФО: {{$decode_company_data->company_mfo}}
                    </li>
                </ul>
            </div>
            <div class="col-6 text-right">
                <img style="width: 200px;height: auto;margin-top: 30px;" src="{{asset('images/logo_pdf.png')}}" alt="{{ config('app.name', 'Laravel') }}">
                <div style="border: 1px solid;padding: 10px;text-align: center;margin-top: 50px">
                    <h4 style="margin-bottom: 0;line-height: 1;padding-bottom: 0"><strong>Акт виконаних робіт</strong></h4>
                    <p style="font-size: 11px;line-height: 1;margin-bottom: 15px">
                        Технічне обслуговування,ремонт<br>
                        <strong>№ {{$check->id}}</strong>
                    </p>
                    <p style="font-size: 14px;line-height: 1;text-align: left;padding-left: 15px">
                        Місце складання {{$check->place}}<br>
                        Дата зверненя {{$check->application_date}}<br>
                        Дата складання {{$check->date_compilation}}<br>
                        Приймальник {{$check->acceptor}}<br>
                        Дата печати {{date('Y-m-d')}}<br>
                    </p>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 20px">
            <div class="col-12">
                <p>Автомобіль: {{$check->client->car_name}}</p>
            </div>
        </div>
        <div class="row" style="margin-bottom: 20px">
            <div class="col-12">
                <table class="table" style="font-size: 13px !important;">
                    <thead>
                    <tr style="font-size: 10px">
                        <td scope="col">Державний номер</td>
                        <td scope="col">Марка</td>
                        <td scope="col">Номер кузова</td>
                        <td scope="col">Дата продажу</td>
                        <td scope="col">Пробіг км</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th>{{$check->client->num_auto}}</th>
                        <th>{{$check->client->brand}}</th>
                        <th>{{$check->client->vin}}</th>
                        <th>{{date('Y-m-d',strtotime($check->client->data))}}</th>
                        <th>{{$check->client->mileage}}</th>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table class="table" style="font-size: 10px !important;">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">№ деталі</th>
                        <th scope="col">Назва</th>
                        <th scope="col">Кількість</th>
                        <th scope="col">Ціна</th>
                        <th scope="col">Ціна зі снижкою</th>
                        <th scope="col">Сума</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $action_price = 0;
                        $material_price = 0;
                        $sum = 0;
                    @endphp
                    @foreach($check->work as $k => $work)
                        @php
                            $price = (isset($work->price_discount) && $work->price_discount > 0)?$work->price_discount * $work->count:$work->price * $work->count;
                            if ($work->type === 'material'){
                                $material_price += $price;
                            }else{
                                $action_price += $price;
                            }
                            $sum += $price;
                        @endphp
                        <tr>
                            <th scope="row">{{$k + 1}}</th>
                            <td>{{$work->article_operation}}</td>
                            <td>{{$work->name}}</td>
                            <td>{{$work->count}}</td>
                            <td>{{(int)$work->price}}грн.</td>
                            <td>{{(int)$work->price_discount}}грн.</td>
                            <td>{{(isset($work->price_discount) && $work->price_discount > 0)?(int)$work->price_discount * $work->count:(int)$work->price * $work->count}}грн.</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-right" style="margin-top: 15px;">
                @if($action_price > 0)
                    <p style="margin-bottom: 0;" class="text-right">Вартість виконаних робіт: {{(int)$action_price}}грн.</p>
                @endif
                @if($material_price > 0)
                    <p style="margin-bottom: 0;"  class="text-right">Вартість встановлених запчастин: {{(int)$material_price}}грн.</p>
                @endif
                <p style="margin-bottom: 0;"  class="text-right"><strong>Всього до сплати:</strong> {{(int)$sum}}грн.</p>
            </div>
        </div>
        @isset($check->price_abc)
            <div class="row">
                <div class="col-12">
                    <p><strong>Всього до сплати прописом: </strong>{{$check->price_abc}}</p>
                </div>
            </div>
        @endisset
        @isset($check->info_for_user)
            <div class="row">
                <div class="col-12">
                    <strong>Інформація для покупця</strong>
                    {!! $check->info_for_user !!}
                </div>
            </div>
        @endisset
        <div class="row" style="margin-top: 50px;">
            <div class="col-6">
                <p style="margin-bottom: 35px;margin-top: 30px;">Обсяг і якість виконаніх робіт перевірив.<br>Автомобіль після обслуговування видав</p>
                <p style="display: inline-block;width: 100px;">Приймальник</p><span style="display: inline-block;height: 1px;width: 100px;background: #cccccc;margin-left: 15px"></span>
            </div>
            <div class="col-6">
                <p style="margin-bottom: 15px;padding-right: 60px">
                    <strong>Автомобіль після ремонту прийняв.</strong><br>
                    Претензій до об'єму і якості виконаних робіт, послуг<br>
                    комплектності автомобіля та його стану не маю.<br>
                    З особливостями експлуатації автомобіля ознайомлений
                </p>
                <p style="display: inline-block;width: 100px;">Замовник</p><span style="display: inline-block;height: 1px;width: 150px;background: #cccccc"></span>
            </div>
        </div>
        @component('admin.component.footer')@endcomponent
    </div>

    @include('admin.component.company_settings')

@endsection
