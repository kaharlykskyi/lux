<input type="hidden" name="user_id" value="{{$user->id}}">

<div class="row form-group">
    <div class="col col-md-3">
        <label for="vin_code" class=" form-control-label">{{__('Номер кузова (VIN код)')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="vin_code" name="vin_code" value="{{old('vin_code')}}" class="form-control">
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="type_auto" class=" form-control-label">{{__('Тип')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <select id="type_auto" name="type_auto" class="form-control">
            <option selected value="passenger">{{__('Легковой')}}</option>
            <option value="commercial">{{__('Грузовой')}}</option>
        </select>
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="year_auto" class=" form-control-label">{{__('Год')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <select id="year_auto" name="year_auto" class="form-control">
            @for($i=(int)date('Y');$i >= 1980;$i--)
                <option value="{{$i}}">{{$i}}</option>
            @endfor
        </select>
    </div>
</div>

<div class="row form-group" id="brand_auto_block" style="display: none">
    <div class="col col-md-3">
        <label for="brand_auto" class=" form-control-label">{{__('Марка')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <select id="brand_auto" name="brand_auto" class="form-control">
            <option></option>
        </select>
    </div>
</div>

<div class="row form-group" id="model_auto_block" style="display: none">
    <div class="col col-md-3">
        <label for="model_auto" class=" form-control-label">{{__('Модель')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <select id="model_auto" name="model_auto" class="form-control">
            <option></option>
        </select>
    </div>
</div>

<div class="row form-group" id="modification_auto_block" style="display: none">
    <div class="col col-md-3">
        <label for="modification_auto" class=" form-control-label">{{__('Модификация')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <select id="modification_auto" name="modification_auto" class="form-control">
            <option></option>
        </select>
    </div>
</div>

<script>
    $('#year_auto').change(function () {
        $('#brand_auto_block').show();
        $.get(`{{route('gat_brands')}}?type_auto=${$('#type_auto').val()}`,function (data) {
            let html = '';
            data.response.forEach(function (item) {
                html += `<option value="${item.id}">${item.description}</option>`;
            });
            $('#brand_auto').html(html);
        });
    });

    $('#brand_auto').change(function () {
        $('#model_auto_block').show();
        $.get(`{{route('gat_model')}}?type_auto=${$('#type_auto').val()}&brand_id=${$('#brand_auto').val()}&year_auto=${$('#year_auto').val()}`,function (data) {
            let html = '';
            data.response.forEach(function (item) {
                html += `<option value="${item.id}">${item.name}</option>`;
            });
            $('#model_auto').html(html);
        });
    });

    $('#model_auto').change(function () {
        $('#modification_auto_block').show();
        $.get(`{{route('get_modifications')}}?type_auto=${$('#type_auto').val()}&model_id=${$('#model_auto').val()}`,function (data) {
            let html = '';
            data.response.forEach(function (item) {
                if (item.attributegroup === 'General' && item.attributetype === 'ConstructionInterval'){
                    html += `<option value="${item.id}">${item.name}</option>`;
                }
            });
            $('#modification_auto').html(html);
        });
    });
</script>

<button type="submit" class="btn btn-primary btn-sm">
    <i class="fa fa-dot-circle-o"></i> {{__('Сохранить')}}
</button>
