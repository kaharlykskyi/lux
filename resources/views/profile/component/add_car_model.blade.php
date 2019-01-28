<div class="modal fade" id="addCar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog login-sec" role="document">
        <div class="modal-content">
            <div class="modal-header bg-blue">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h6 class="modal-title text-white" id="myModalLabel">Добавить авто</h6>
            </div>
            <div class="modal-body">
                <form type="POST" action="{{route('add_car')}}" class="ajax-form ajax1" data-add-block="true" data-id-add-block="addedCars">
                    @csrf
                    <ul class="row login-sec">
                        <li class="col-sm-12">
                            <label>{{ __('VIN код') }}
                                <input type="text" class="form-control" name="vin_code" value="" required autofocus>
                            </label>
                        </li>
                        <li class="col-sm-12" id="type_auto">
                            <label>{{ __('Тип автомобиля') }}
                                <select class="form-control selectpicker" data-live-search="true" name="type_auto" required>
                                    <option label="" value="0"></option>
                                    <option label="{{__('Легковой')}}" value="passenger">{{__('Легковой')}}</option>
                                    <option label="{{__('Грузовой')}}" value="commercial">{{__('Грузовой')}}</option>
                                </select>
                            </label>
                        </li>
                        <li class="col-sm-12 hidden" id="year_auto">
                            <label>{{ __('Год выпуска') }}
                                <select class="form-control selectpicker"  data-live-search="true" name="year_auto" required>
                                    <option label="" value="0"></option>
                                    @for($i = (integer)date('Y'); $i > 1970; $i--)
                                        <option label="{{$i}}" value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </label>
                        </li>
                        <li class="col-sm-12 hidden" id="brand_auto">
                            <label>{{ __('Марка') }}
                                <select class="form-control selectpicker" data-live-search="true" name="brand_auto" required>
                                    <option label="" value="0">{{__('загрузка...')}}</option>
                                </select>
                            </label>
                        </li>
                        <li class="col-sm-12 hidden" id="model_auto">
                            <label>{{ __('Модель') }}
                                <select class="form-control selectpicker" data-live-search="true" name="model_auto" required>
                                    <option label="" value="0">{{__('загрузка...')}}</option>
                                </select>
                            </label>
                        </li>
                        <li class="col-sm-12 hidden" id="modification_auto">
                            <label>{{ __('Модификация') }}
                                <select class="form-control selectpicker" data-live-search="true" name="modification_auto" required>
                                    <option label="" value="0">{{__('загрузка...')}}</option>
                                </select>
                            </label>
                        </li>
                        <li class="col-sm-12 hidden" id="body_auto">
                            <label>{{ __('Тип кузова') }}
                                <select class="form-control selectpicker" data-live-search="true" name="body_auto" required>
                                    <option label="" value="0">{{__('загрузка...')}}</option>
                                </select>
                            </label>
                        </li>
                        <li class="col-sm-12 hidden" id="type_motor">
                            <label>{{ __('Тип двигателя') }}
                                <select class="form-control selectpicker" data-live-search="true" name="type_motor" required>
                                    <option label="" value="0">{{__('загрузка...')}}</option>
                                </select>
                            </label>
                        </li>
                        <li class="col-sm-12 error-response"></li>
                        <li class="col-sm-12 text-left">
                            <button disabled="disabled" id="add-car-btn" type="submit" style="cursor: not-allowed;" class="btn-round">{{__('Добавить')}}</button>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#type_auto select').change(function () {
            $('#year_auto').removeClass('hidden');

            $.get(`{{route('gat_brands')}}?type_auto=${$('#type_auto select').val()}`, function(data) {
                let str_data = `<option label="" value="0"></option>`;
                data.response.forEach(function (item) {
                    str_data += `<option label="${item.description}" value="${item.id}">${item.description}</option>`
                });
                $('#brand_auto select').html(str_data).selectpicker('refresh');
                $('#brand_auto').removeClass('hidden');
            });
        });
        $('#brand_auto select').change(function () {
            $('#model_auto').removeClass('hidden');
            $.get(`{{route('gat_model')}}?type_auto=${$('#type_auto select').val()}&brand_id=${$('#brand_auto select').val()}&year_auto=${$('#year_auto select').val()}`, function(data) {
                let str_data = `<option label="" value="0"></option>`;
                data.response.forEach(function (item) {
                    str_data += `<option label="${item.name}" value="${item.id}">${item.name}</option>`
                });
                $('#model_auto select').html(str_data).selectpicker('refresh');
            });
        });
        $('#model_auto select').change(function () {
            $('#modification_auto').removeClass('hidden');
            $('#body_auto').removeClass('hidden');
            $('#type_motor').removeClass('hidden');
            $.get(`{{route('get_modifications')}}?type_auto=${$('#type_auto select').val()}&model_id=${$('#model_auto select').val()}&type_mod=General`, function(data) {
                let str_data = `<option label="" value="0"></option>`;
                data.response.forEach(function (item) {
                    str_data += `<option label="${item.name}" value="${item.id}">${item.name}</option>`
                });
                $('#modification_auto select').html(str_data).selectpicker('refresh');
            });
            $.get(`{{route('get_modifications')}}?type_auto=${$('#type_auto select').val()}&model_id=${$('#model_auto select').val()}&type_mod=Body`, function(data) {
                let str_data = `<option label="" value="0"></option>`;
                data.response.forEach(function (item) {
                    str_data += `<option label="${item.displayvalue}" value="${item.displayvalue}">${item.displayvalue}</option>`
                });
                $('#body_auto select').removeAttr('disabled').html(str_data).selectpicker('refresh');
            });
            $.get(`{{route('get_modifications')}}?type_auto=${$('#type_auto select').val()}&model_id=${$('#model_auto select').val()}&type_mod=Engine`, function(data) {
                let str_data = `<option label="" value="0"></option>`;
                data.response.forEach(function (item) {
                    str_data += `<option label="${item.displayvalue}" value="${item.displayvalue}">${item.displayvalue}</option>`
                });
                $('#type_motor select').removeAttr('disabled').html(str_data).selectpicker('refresh');
            });
        });

        $('#modification_auto select').change(function () {
            $('#add-car-btn').removeAttr('disabled').css('cursor','pointer')
        });
    });
</script>