<div class="col-xs-12 filter-section padding-10">
    <form action="{{route('vin_decode')}}" method="post">
        @csrf
        <div class="row">
            <div class="col-xs-12 col-sm-10">
                <input class="form-control" type="text" @isset($vin)value="{{$vin}}"@endisset name="vin" placeholder="Например: JTEHT05JX02054465">
            </div>
            <div class="col-xs-12 col-sm-2">
                <button type="submit" class="btn-round btn-sm">{{__('Подобрать')}}</button>
            </div>
        </div>
    </form>
</div>

<div class="col-xs-12 filter-section padding-10">
    <ul class="nav nav-pills">
        <li role="presentation" onclick="$.post(`{{route('vin_decode.catalog')}}`,{'vin_catalog_type':'quickGroup','_token':'{{csrf_token()}}'},function() {location.reload();});" id="quickGroup"><a href="#">{{__('Поиск по групам')}}</a></li>
        <li role="presentation" onclick="$.post(`{{route('vin_decode.catalog')}}`,{'vin_catalog_type':'listUnits','_token':'{{csrf_token()}}'},function() {location.reload();});" id="listUnits"><a href="#">{{__('Поиск по картинкам')}}</a></li>
    </ul>
</div>
<script>
    $(document).ready(function () {
        const type_catalog = '{{Cookie::get('vin_catalog')}}';
        if (type_catalog === 'quickGroup'){
            $('#quickGroup').addClass('active');
        } else if(type_catalog === 'listUnits') {
            $('#listUnits').addClass('active');
        }
    });
</script>
<div class="col-xs-12">
    @if (session('status'))
        <div class="row">
            <div class="col-xs-12">
                <div class="alert alert-danger" role="alert">
                    {{ session('status') }}
                </div>
            </div>
        </div>
    @endif
</div>