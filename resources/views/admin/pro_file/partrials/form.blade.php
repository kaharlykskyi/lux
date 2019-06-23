<div class="row form-group">
    <div class="col col-md-3">
        <label for="title" class=" form-control-label">{{__('Название')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="name" name="name" value="@if(isset($proFile->id)){{$proFile->name}}@else{{old('name')}}@endif" placeholder="{{__('Название профайла...')}}" class="form-control" required>
        @if ($errors->has('name'))
            <small class="form-text text-danger">{{ $errors->first('name') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="provider_id" class=" form-control-label">{{__('Поставщик')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <select name="provider_id" id="provider_id" class="form-control">
            @foreach($providers as $provider)
                <option @isset($proFile->id) @if($proFile->provider_id == $provider->id) selected @endif @endisset value="{{$provider->id}}">{{$provider->name}}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="currency" class=" form-control-label">{{__('Валюта')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <select name="currency" id="currency" class="form-control" required>
            <option @isset($proFile->id) @if($proFile->currency === 'UAH') selected @endif @endisset value="UAH">{{__('UAH')}}</option>
            <option @isset($proFile->id) @if($proFile->currency === 'EUR') selected @endif @endisset value="EUR">{{__('EUR')}}</option>
            <option @isset($proFile->id) @if($proFile->currency === 'USD') selected @endif @endisset value="USD">{{__('USD')}}</option>
        </select>
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="col_provider" class=" form-control-label">{{__('№ Колонки поставщика')}}<small class="form-text text-info">{{__('если не присвоен поставщик')}}</small></label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="col_provider" name="col_provider" value="@if(isset($proFile->id)){{$proFile->col_provider}}@else{{old('col_provider')}}@endif" class="form-control">
        @if ($errors->has('col_provider'))
            <small class="form-text text-danger">{{ $errors->first('col_provider') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="data_row" class=" form-control-label">{{__('№ строки начала загрузки')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="data_row" name="data_row" value="@if(isset($proFile->id)){{$proFile->data_row}}@else{{old('data_row')}}@endif" class="form-control" required>
        @if ($errors->has('data_row'))
            <small class="form-text text-danger">{{ $errors->first('data_row') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="articles" class=" form-control-label">{{__('№ Колонки кода запчасти')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="articles" name="articles" value="@if(isset($proFile->id)){{$proFile->articles}}@else{{old('articles')}}@endif" class="form-control" required>
        @if ($errors->has('articles'))
            <small class="form-text text-danger">{{ $errors->first('articles') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="product_name" class=" form-control-label">{{__('№ Колонки названия')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="product_name" name="product_name" value="@if(isset($proFile->id)){{$proFile->product_name}}@else{{old('product_name')}}@endif" class="form-control" required>
        @if ($errors->has('product_name'))
            <small class="form-text text-danger">{{ $errors->first('product_name') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="price" class=" form-control-label">{{__('№ Колонки закупочной цены')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="price" name="price" value="@if(isset($proFile->id)){{$proFile->price}}@else{{old('price')}}@endif" class="form-control" required>
        @if ($errors->has('price'))
            <small class="form-text text-danger">{{ $errors->first('price') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="brand" class=" form-control-label">{{__('№ Колонки названия бренда')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="brand" name="brand" value="@if(isset($proFile->id)){{$proFile->brand}}@else{{old('brand')}}@endif" class="form-control" required>
        @if ($errors->has('brand'))
            <small class="form-text text-danger">{{ $errors->first('brand') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="data_row" class=" form-control-label">{{__('№ Колонки доступного кол-ва, через запятую')}}</label>
        <span class="small">Название складов писать после номера колонки через /</span>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="stocks" name="stocks" value="@if(isset($proFile->id)){{$proFile->stocks}}@else{{old('stocks')}}@endif" class="form-control" required>
        @if ($errors->has('stocks'))
            <small class="form-text text-danger">{{ $errors->first('stocks') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="delivery_time" class=" form-control-label">{{__('№ Колонки срока поставки')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="delivery_time" name="delivery_time" value="@if(isset($proFile->id)){{$proFile->delivery_time}}@else{{old('delivery_time')}}@endif" class="form-control">
        @if ($errors->has('delivery_time'))
            <small class="form-text text-danger">{{ $errors->first('delivery_time') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="active_sheet" class=" form-control-label">{{__('№ Листа с данными')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="number" id="active_sheet" name="active_sheet" value="@if(isset($proFile->id)){{$proFile->active_sheet}}@else{{1}}@endif" class="form-control">
        @if ($errors->has('active_sheet'))
            <small class="form-text text-danger">{{ $errors->first('active_sheet') }}</small>
        @endif
    </div>
</div>

<hr>
<div class="row form-group">
    <div class="col col-md-3">
        <label for="exchange_range" class=" form-control-label">{{__('Курс валюты')}}</label><br>
        <span class="small text-info">По дефолту курс Привата</span>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="exchange_range" name="exchange_range" value="@if(isset($proFile->id)){{$proFile->exchange_range}}@else{{old('exchange_range')}}@endif" class="form-control">
        @if ($errors->has('exchange_range'))
            <small class="form-text text-danger">{{ $errors->first('exchange_range') }}</small>
        @endif
    </div>
</div>


<div class="row form-group">
    <div class="col col-md-3">
        <label for="exchange_range" class=" form-control-label">{{__('Наценка товара')}}</label><br>
        <span class="small text-info">По дефолту:<2000:20%,<5000:15%,>5000:10%</span>
    </div>
    <div class="col-12 col-md-9">
        <input type="hidden" name="markup" id="markup_input">
        <div id="markup-block">
            @if(isset($proFile->id) && isset($proFile->markup))
                @php $markup_val = json_decode($proFile->markup) @endphp
                @forelse($markup_val as $k => $item)
                    @component('admin.pro_file.partrials.markup_input',['data' => $item,'k' => $k])@endcomponent
                @empty
                    @component('admin.pro_file.partrials.markup_input')@endcomponent
                @endforelse
            @else
                @component('admin.pro_file.partrials.markup_input')@endcomponent
            @endif
        </div>
        <button onclick="addMarkup()" type="button" class="btn btn-success btn-sm">
            {{__('Добавить')}}
        </button>
    </div>
</div>

<hr>
<p class="h5">Настройки для сбора прайсов из почтового ящика </p>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="static_name" class=" form-control-label">{{__('Постоянная часть имени файла для email или ftp')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="static_name" name="static_name" value="@if(isset($proFile->id)){{$proFile->static_name}}@else{{old('static_name')}}@endif" class="form-control" required>
        @if ($errors->has('static_name'))
            <small class="form-text text-danger">{{ $errors->first('static_name') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="static_name" class=" form-control-label">{{__('E-mail 1')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="email" id="static_email1" name="static_email1" value="@if(isset($proFile->id)){{$proFile->static_email1}}@else{{old('static_email1')}}@endif" class="form-control">
        @if ($errors->has('static_email1'))
            <small class="form-text text-danger">{{ $errors->first('static_email1') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="static_email2" class=" form-control-label">{{__('E-mail 2')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="email" id="static_email2" name="static_email2" value="@if(isset($proFile->id)){{$proFile->static_email2}}@else{{old('static_email2')}}@endif" class="form-control">
        @if ($errors->has('static_email2'))
            <small class="form-text text-danger">{{ $errors->first('static_email2') }}</small>
        @endif
    </div>
</div>


<button onclick="submitProfileForm()" type="button" class="btn btn-primary btn-sm">
    <i class="fa fa-dot-circle-o"></i> {{__('Сохранить')}}
</button>
<script>
    function deleteMarkupRow(id) {
        $('#markup_row_'+id).remove();
    }

    function submitProfileForm() {
        const markup_rows = $('#markup-block .form-row');
        let markup = [];

        for (let i = 0;i < markup_rows.length;i++){
            let inputs = $(markup_rows[i]).find('input');

            if (parseInt($(inputs[1]).val().trim()) > 0){
                markup.push({
                    'min': parseInt($(inputs[0]).val().trim()),
                    'max': parseInt($(inputs[1]).val().trim()),
                    'markup': parseInt($(inputs[2]).val().trim())
                });
            }
        }

        $('#markup_input').val(JSON.stringify(markup));
        $('#pro_file_form').submit()
    }

    function addMarkup() {
        $('#markup-block').append(`
            @component('admin.pro_file.partrials.markup_input')@endcomponent
        `);
    }
</script>
