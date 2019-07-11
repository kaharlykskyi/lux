<input type="hidden" name="sto_clint_id" value="{{request('client')}}">

<div class="row form-group">
    <div class="col col-md-3">
        <label for="place" class=" form-control-label">{{__('Место составления')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="place" name="place" value="@if(isset($check->id)){{$check->place}}@else{{old('place')}}@endif" class="form-control">
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
        <input type="text" id="acceptor" name="acceptor" value="@if(isset($check->id)){{$check->acceptor}}@else{{old('acceptor')}}@endif" class="form-control">
        @if ($errors->has('acceptor'))
            <small class="form-text text-danger">{{ $errors->first('acceptor') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="application_date" class=" form-control-label">{{__('Дата заявки')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="date" id="application_date" name="application_date" value="@if(isset($check->id)){{date('Y-m-d',strtotime($check->application_date))}}@else{{old('application_date')}}@endif" class="form-control">
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
        <input type="date" id="date_compilation" name="date_compilation" value="@if(isset($check->id)){{date('Y-m-d',strtotime($check->date_compilation))}}@else{{old('date_compilation')}}@endif" class="form-control">
        @if ($errors->has('date_compilation'))
            <small class="form-text text-danger">{{ $errors->first('date_compilation') }}</small>
        @endif
    </div>
</div>

<div class="form-group">
    <label for="info_for_user">Информация для покупателя</label>
    <textarea class="form-control" name="info_for_user" id="info_for_user" rows="5">@if(isset($check->id)){{$check->info_for_user}}@else{{old('info_for_user')}}@endif</textarea>
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
                <th scope="col">Название</th>
                <th scope="col">Кол.</th>
                <th scope="col">Тип</th>
                <th scope="col">Цена</th>
                <th scope="col">Сумма</th>
            </tr>
            </thead>
            <tbody id="work_block">
            @if(isset($check->work))
                @foreach($check->work as $k => $work)
                    <tr>
                        <th scope="row">
                            <div style="width: 30px;">
                                <i onclick="deleteWork(this,'{{$work->id}}')" style="cursor: pointer" class="fa fa-trash" aria-hidden="true"></i>
                                <input type="hidden" name="id[]" value="{{$work->id}}">{{$k + 1}}
                            </div>
                        </th>
                        <td><input style="background: #cccccc5e;padding: 5px;" type="text" required name="product_name[]" value="{{isset($work->article_operation)?"{$work->article_operation}/{$work->name}":$work->name}}"></td>
                        <td><input style="background: #cccccc5e;padding: 5px;" type="text" name="product_col[]" value="{{$work->count}}"></td>
                        <td>
                            <select style="background: #cccccc5e;padding: 5px;" name="type[]">
                                <option @if($work->type === 'material') selected @endif value="material">материал</option>
                                <option @if($work->type === 'action') selected @endif value="action">действие</option>
                            </select>
                        </td>
                        <td><input style="background: #cccccc5e;padding: 5px;" type="text" name="product_price[]" value="{{round($work->price,2)}}"></td>
                        <td><input onblur="sumProduct()" class="product_sum" style="background: #cccccc5e;padding: 5px;" type="text" name="product_sum[]" value="{{round((float)(($work->price_discount > 0)?$work->price_discount : $work->price) * $work->count,2)}}"></td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <th scope="row">1</th>
                    <td><input style="background: #cccccc5e;padding: 5px;" type="text" name="product_name[]" value=""></td>
                    <td><input style="background: #cccccc5e;padding: 5px;" type="text" name="product_col[]" value=""></td>
                    <td>
                        <select style="background: #cccccc5e;padding: 5px;" name="type[]">
                            <option selected value="material">материал</option>
                            <option value="action">действие</option>
                        </select>
                    </td>
                    <td><input style="background: #cccccc5e;padding: 5px;" type="text" name="product_price[]" value=""></td>
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
            if (isset($check->work)){
                $sum = 0;
                foreach ($check->work as $work){
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
        <input type="text" id="price_abc" name="price_abc" value="@if(isset($check->id)){{$check->price_abc}}@else{{old('price_abc')}}@endif" class="form-control">
        @if ($errors->has('price_abc'))
            <small class="form-text text-danger">{{ $errors->first('price_abc') }}</small>
        @endif
    </div>
</div>

@isset($check->id)
    <input id="delete_work" type="hidden" name="delete_work" value="">
@endisset

<button type="button" onclick="submit_sto_check_form(this)" class="btn btn-primary btn-sm">
    <i class="fa fa-dot-circle-o"></i> {{__('Сохранить')}}
</button>

<script>
    function submit_sto_check_form(obj){
        $('#sto_check_form').submit();
        $(obj).attr('disabled','disabled')
    }

    CKEDITOR.replace( 'info_for_user' );

    @isset($check->id)
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
                        @isset($check->id)
            <input type="hidden" name="id[]" value="new">
@endisset
            </th>
            <td><input style="background: #cccccc5e;padding: 5px;" type="text" name="product_name[]" required value=""></td>
            <td><input style="background: #cccccc5e;padding: 5px;" type="text" name="product_col[]" value=""></td>
            <td>
                <select style="background: #cccccc5e;padding: 5px;" name="type[]">
                    <option selected value="material">материал</option>
                    <option value="action">действие</option>
                </select>
            </td>
            <td><input style="background: #cccccc5e;padding: 5px;" type="text" name="product_price[]" value=""></td>
            <td><input class="product_sum" onblur="sumProduct()" style="background: #cccccc5e;padding: 5px;" type="text" name="product_sum[]" value=""></td>
        </tr>`);
    }
</script>
