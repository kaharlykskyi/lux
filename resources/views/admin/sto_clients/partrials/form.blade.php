<div class="row form-group">
    <div class="col col-md-3">
        <label for="fio" class=" form-control-label">{{__('ФИО')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="fio" name="fio" value="@if(isset($sto_client->id)){{$sto_client->fio}}@else{{old('fio')}}@endif" class="form-control" required>
        @if ($errors->has('fio'))
            <small class="form-text text-danger">{{ $errors->first('fio') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="car_name" class=" form-control-label">{{__('Автомобиль')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="car_name" name="car_name" value="@if(isset($sto_client->id)){{$sto_client->car_name}}@else{{old('car_name')}}@endif" class="form-control">
        @if ($errors->has('car_name'))
            <small class="form-text text-danger">{{ $errors->first('car_name') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="num_auto" class=" form-control-label">{{__('Номер авто')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="num_auto" name="num_auto" value="@if(isset($sto_client->id)){{$sto_client->num_auto}}@else{{old('num_auto')}}@endif" class="form-control" required>
        @if ($errors->has('num_auto'))
            <small class="form-text text-danger">{{ $errors->first('num_auto') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="brand" class=" form-control-label">{{__('Авто (марка)')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="brand" name="brand" value="@if(isset($sto_client->id)){{$sto_client->brand}}@else{{old('num_auto')}}@endif" class="form-control" required>
        @if ($errors->has('brand'))
            <small class="form-text text-danger">{{ $errors->first('brand') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="mileage" class=" form-control-label">{{__('Пробег')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="mileage" name="mileage" value="@if(isset($sto_client->id)){{$sto_client->mileage}}@else{{old('mileage')}}@endif" class="form-control">
        @if ($errors->has('mileage'))
            <small class="form-text text-danger">{{ $errors->first('mileage') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="vin" class=" form-control-label">{{__('VIN код')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="vin" name="vin" value="@if(isset($sto_client->id)){{$sto_client->vin}}@else{{old('vin')}}@endif" class="form-control" required>
        @if ($errors->has('vin'))
            <small class="form-text text-danger">{{ $errors->first('vin') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="data" class=" form-control-label">{{__('Дата продажи')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="date" id="data" name="data" value="@if(isset($sto_client->id)){{date('Y-m-d',strtotime($sto_client->data))}}@else{{old('data')}}@endif" class="form-control">
        @if ($errors->has('data'))
            <small class="form-text text-danger">{{ $errors->first('data') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="place" class=" form-control-label">{{__('Место составления')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="place" name="place" value="@if(isset($sto_client->id)){{$sto_client->place}}@else{{old('place')}}@endif" class="form-control">
        @if ($errors->has('place'))
            <small class="form-text text-danger">{{ $errors->first('place') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="acceptor" class=" form-control-label">{{__('Приниматель')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="acceptor" name="acceptor" value="@if(isset($sto_client->id)){{$sto_client->acceptor}}@else{{old('acceptor')}}@endif" class="form-control">
        @if ($errors->has('acceptor'))
            <small class="form-text text-danger">{{ $errors->first('acceptor') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="acceptor" class=" form-control-label">{{__('Телефон')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="phone" name="phone" value="@if(isset($sto_client->id)){{$sto_client->phone}}@else{{old('phone')}}@endif" class="form-control">
        @if ($errors->has('phone'))
            <small class="form-text text-danger">{{ $errors->first('phone') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="application_date" class=" form-control-label">{{__('Дата заявки')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="date" id="application_date" name="application_date" value="@if(isset($sto_client->id)){{date('Y-m-d',strtotime($sto_client->application_date))}}@else{{old('application_date')}}@endif" class="form-control">
        @if ($errors->has('application_date'))
            <small class="form-text text-danger">{{ $errors->first('application_date') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="date_compilation" class=" form-control-label">{{__('Дата составления')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="date" id="date_compilation" name="date_compilation" value="@if(isset($sto_client->id)){{date('Y-m-d',strtotime($sto_client->date_compilation))}}@else{{old('date_compilation')}}@endif" class="form-control">
        @if ($errors->has('date_compilation'))
            <small class="form-text text-danger">{{ $errors->first('date_compilation') }}</small>
        @endif
    </div>
</div>

<div class="form-group">
    <label for="info_for_user">Информация для покупателя</label>
    <textarea class="form-control" name="info_for_user" id="info_for_user" rows="5">@if(isset($sto_client->id)){{$sto_client->info_for_user}}@else{{old('info_for_user')}}@endif</textarea>
</div>

<hr>

<div class="row">
    <div class="col-12">
        <p class="h6">Выполненые работы</p>
    </div>
    <div class="col-12 table-responsive">
        <table class="table" style="font-size: 13px !important;">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">№ материала/действия</th>
                <th scope="col">Название</th>
                <th scope="col">Кол.</th>
                <th scope="col">Тип</th>
                <th scope="col">Цена</th>
                <th scope="col">Цена со скидкой</th>
                <th scope="col">Сумма</th>
            </tr>
            </thead>
            <tbody id="work_block">
            @if(isset($sto_client->work))
                @foreach($sto_client->work as $k => $work)
                    <tr>
                        <th scope="row">
                            <div style="width: 30px;">
                                <i onclick="deleteWork(this,'{{$work->id}}')" style="cursor: pointer" class="fa fa-trash" aria-hidden="true"></i>
                                <input type="hidden" name="id[]" value="{{$work->id}}">{{$k + 1}}
                            </div>
                        </th>
                        <td><input style="background: #cccccc5e;padding: 5px;" type="text" name="product_article[]" value="{{$work->article_operation}}"></td>
                        <td><input style="background: #cccccc5e;padding: 5px;" type="text" name="product_name[]" value="{{$work->name}}"></td>
                        <td><input style="background: #cccccc5e;padding: 5px;" type="text" name="product_col[]" value="{{$work->count}}"></td>
                        <td>
                            <select style="background: #cccccc5e;padding: 5px;" name="type[]">
                                <option @if($work->type === 'material') selected @endif value="material">материал</option>
                                <option @if($work->type === 'action') selected @endif value="action">действие</option>
                            </select>
                        </td>
                        <td><input style="background: #cccccc5e;padding: 5px;" type="text" name="product_price[]" value="{{round($work->price,2)}}"></td>
                        <td><input style="background: #cccccc5e;padding: 5px;" type="text" name="product_price_discount[]" value="{{round($work->price_discount,2)}}"></td>
                        <td><input onblur="sumProduct()" class="product_sum" style="background: #cccccc5e;padding: 5px;" type="text" name="product_sum[]" value="{{round((float)(($work->price_discount > 0)?$work->price_discount : $work->price) * $work->count,2)}}"></td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <th scope="row">1</th>
                    <td><input style="background: #cccccc5e;padding: 5px;" type="text" name="product_article[]" value=""></td>
                    <td><input style="background: #cccccc5e;padding: 5px;" type="text" name="product_name[]" value=""></td>
                    <td><input style="background: #cccccc5e;padding: 5px;" type="text" name="product_col[]" value=""></td>
                    <td>
                        <select style="background: #cccccc5e;padding: 5px;" name="type[]">
                            <option selected value="material">материал</option>
                            <option value="action">действие</option>
                        </select>
                    </td>
                    <td><input style="background: #cccccc5e;padding: 5px;" type="text" name="product_price[]" value=""></td>
                    <td><input style="background: #cccccc5e;padding: 5px;" type="text" name="product_price_discount[]" value=""></td>
                    <td><input onblur="sumProduct()" class="product_sum" style="background: #cccccc5e;padding: 5px;" type="text" name="product_sum[]" value=""></td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>

<div class="row justify-content-end" style="padding: 15px">
    <div class="col-12 text-right">
        <button type="button" onclick="addWorkRow();" class="au-btn au-btn-icon au-btn--green au-btn--small">
            <i class="zmdi zmdi-plus"></i>{{__('Добавить поле')}}
        </button>
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="sum" class=" form-control-label">{{__('Итоговая сумма')}}</label>
    </div>
    <div class="col-12 col-md-9">
        @php
            if (isset($sto_client->work)){
                $sum = 0;
                foreach ($sto_client->work as $work){
                    $sum += round((float)(($work->price_discount > 0)?$work->price_discount : $work->price) * $work->count,2);
                }
            }
        @endphp
        <input type="number" id="sum" name="sum" value="@if(isset($sum)){{$sum}}@else{{old('sum')}}@endif" class="form-control">
        @if ($errors->has('sum'))
            <small class="form-text text-danger">{{ $errors->first('sum') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="price_abc" class=" form-control-label">{{__('Всего к оплате буквами')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="price_abc" name="price_abc" value="@if(isset($sto_client->id)){{$sto_client->price_abc}}@else{{old('price_abc')}}@endif" class="form-control">
        @if ($errors->has('price_abc'))
            <small class="form-text text-danger">{{ $errors->first('price_abc') }}</small>
        @endif
    </div>
</div>

@isset($sto_client->id)
    <input id="delete_work" type="hidden" name="delete_work" value="">
@endisset

<button type="submit" class="btn btn-primary btn-sm">
    <i class="fa fa-dot-circle-o"></i> {{__('Сохранить')}}
</button>

<script>
    CKEDITOR.replace( 'info_for_user' );

    @isset($sto_client->id)
        function deleteWork(obj,id) {
            let delete_id = $('#delete_work').val().split(',');
            if (delete_id.includes(id)){
                $(obj).removeClass('text-danger');
                let delete_ids = [];
                for (let item in delete_id){
                    if (item !== id && item !== ''){
                        delete_ids.push(item);
                    }
                }

                console.log(delete_ids);
                $('#delete_work').val(delete_ids.join(','));
            } else {
                delete_id.push(id);
                $(obj).addClass('text-danger');
                $('#delete_work').val(delete_id.join(','));
            }

        }
    @endisset

    function sumProduct() {
        const sum_data = $('.product_sum');
        let sum = 0;
        for (let i = 0; i < sum_data.length;i++){
            sum += parseFloat(sum_data[i].value);
        }
        $('#sum').val(Number((sum).toFixed(2)));
    }

    function addWorkRow() {
        const count = $('#work_block tr').length;
        $('#work_block').append(`<tr>
                    <th scope="row">
                        ${count + 1}
                        @isset($sto_client->id)
                            <input type="hidden" name="id[]" value="new">
                        @endisset
                    </th>
                    <td><input style="background: #cccccc5e;padding: 5px;" type="text" name="product_article[]" value=""></td>
                    <td><input style="background: #cccccc5e;padding: 5px;" type="text" name="product_name[]" value=""></td>
                    <td><input style="background: #cccccc5e;padding: 5px;" type="text" name="product_col[]" value=""></td>
                    <td>
                        <select style="background: #cccccc5e;padding: 5px;" name="type[]">
                            <option selected value="material">материал</option>
                            <option value="action">действие</option>
                        </select>
                    </td>
                    <td><input style="background: #cccccc5e;padding: 5px;" type="text" name="product_price[]" value=""></td>
                    <td><input style="background: #cccccc5e;padding: 5px;" type="text" name="product_price_discount[]" value=""></td>
                    <td><input class="product_sum" onblur="sumProduct()" style="background: #cccccc5e;padding: 5px;" type="text" name="product_sum[]" value=""></td>
                </tr>`);
    }
</script>
