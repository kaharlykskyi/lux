<div class="panel panel-primary">
    <div class="panel-heading">{{__('Мои автомобили')}}</div>
    <div class="panel-body panel-profile">
        <div class="row login-sec">
            <div class="col-sm-12 text-right">
                <button type="button" data-toggle="modal" data-target="#addCar" class="btn-round">{{__('Добавить автомобиль')}}</button>
            </div>
            <div class="col-sm-12">
                <div class="list-group padding-top-10" id="addedCars">
                    @isset($user_cars)
                        @foreach($user_cars as $user_car)
                            <a href="#" class="list-group-item" id="car-block{{$user_car->id}}">
                                <p class="text-right">
                                    <button class="delete-car-btn" onclick="deleteCar({{$user_car->id}})" title="{{__('Удалить машину')}}">
                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                    </button>
                                </p>
                                <p class="list-group-item-text"><strong>VIN код:</strong> {{$user_car->vin_code}}</p>
                                <p class="list-group-item-text"><strong>Тип:</strong> {{$user_car->type_auto}}</p>
                                <p class="list-group-item-text"><strong>Год выпуска:</strong> {{$user_car->year_auto}}</p>
                                <p class="list-group-item-text"><strong>Марка:</strong> {{$user_car->brand_auto}}</p>
                                <p class="list-group-item-text"><strong>Модель:</strong> {{$user_car->model_auto}}</p>
                                <p class="list-group-item-text"><strong>Модификация:</strong> {{$user_car->modification_auto}}</p>
                                <p class="list-group-item-text"><strong>Тип кузова:</strong> {{$user_car->body_auto}}</p>
                                <p class="list-group-item-text"><strong>Тип двигателя:</strong> {{$user_car->type_motor}}</p>
                            </a>
                        @endforeach
                    @endisset
                </div>
            </div>
        </div>
    </div>
</div>