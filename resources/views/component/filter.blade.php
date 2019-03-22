<section class="filter-section">

    <div class="container padding-top-20 padding-bottom-20">
        <section id="fancyTabWidget" class="tabs t-tabs">
            <ul class="nav nav-tabs fancyTabs" role="tablist">
                <li class="tab fancyTab active">
                    <div class="arrow-down"><div class="arrow-down-inner"></div></div>
                    <a id="tab1" href="#tabBody1" role="tab" aria-controls="tabBody1" aria-selected="true" data-toggle="tab" tabindex="0">
                        <span>{{__('Подбор по авто')}}</span>
                    </a>
                    <div class="whiteBlock"></div>
                </li>

                <li class="tab fancyTab">
                    <div class="arrow-down"><div class="arrow-down-inner"></div></div>
                    <a id="tab2" href="#tabBody2" role="tab" aria-controls="tabBody2" aria-selected="true" data-toggle="tab" tabindex="0">
                        <span>{{__('Подбор по ')}}</span><strong>{{__('VIN')}}</strong>
                    </a>
                    <div class="whiteBlock"></div>
                </li>
            </ul>
            <div id="myTabContent" class="tab-content fancyTabContent" aria-live="polite">
                <div class="tab-pane active in fade" id="tabBody1" role="tabpanel" aria-labelledby="tab1" aria-hidden="true" tabindex="0">
                    <div class="row">

                        <div class="col-md-12">
                            <form method="post" action="{{route('get_section_part')}}" id="search-detail-car-form">
                                @csrf
                                <div class="row padding-30">
                                    <div class="col-xs-12" id="filter-cars-block">
                                        <div id="history-car" style="display: none"></div>
                                        <ul class="search-car__list">
                                            <li>
                                                <select class="filter_select" name="type_auto" id="type_auto">
                                                    <option selected value="passenger">{{__('Легковой')}}</option>
                                                    <option value="commercial">{{__('Грузовой')}}</option>
                                                </select>
                                            </li>
                                            <li class="year_auto">
                                                <select class="filter_select" name="year_auto" id="year_auto">
                                                    <option selected value="">{{__('Год')}}</option>
                                                    @for($i=(int)date('Y');$i >= 1980;$i--)
                                                        <option value="{{$i}}">{{$i}}</option>
                                                    @endfor
                                                </select>
                                            </li>
                                            <li class="brand_auto">
                                                <select class="filter_select" name="brand_auto" id="brand_auto">
                                                    <option selected value="">{{__('Марка')}}</option>
                                                </select>
                                            </li>
                                            <li>
                                                <select class="filter_select" name="model_auto" id="model_auto">
                                                    <option selected value="">{{__('Модель')}}</option>
                                                </select>
                                            </li>
                                            <li>
                                                <select class="filter_select" name="body_auto" id="body_auto">
                                                    <option selected value="">{{__('Кузов')}}</option>
                                                </select>
                                            </li>
                                            <li>
                                                <select class="filter_select" name="engine_auto" id="engine_auto">
                                                    <option selected value="">{{__('Двигатель')}}</option>
                                                </select>
                                            </li>
                                            <li>
                                                <select class="filter_select" name="modification_auto" id="modification_auto">
                                                    <option selected value="">{{__('Модификация')}}</option>
                                                </select>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-xs-12 padding-top-10">
                                        <a class="link" href="" onclick="return false;" data-toggle="modal" data-target="#search_cars_modal">{{__('Мой гараж')}}</a>
                                    </div>
                                </div>
                                <div class="row margin-top-10 hidden" id="search-detail-car">
                                    <div class="col-xs-12 text-center">
                                        <img id="car_f" src="" alt="">
                                        <img id="car_s" src="" alt="">
                                    </div>
                                    <div class="col-xs-12 text-center">
                                        <button type="submit" style="width: 200px;" class="btn-round btn-sm">{{__('Подобрать')}}</button>
                                    </div>
                                </div>
                            </form>
                            <div class="col-xs-12 margin-top-10" style="display: none;" id="root-category-modification-wrapper">
                                <p class="h4 text-center">{{__('Категории в которых искать детали')}}</p>
                                <div id="root-category-modification"></div>
                            </div>
                            <script>
                                $(function() {
                                    $('select.filter_select').selectric();
                                    $(document).ready(function () {
                                        dataFilter(0);
                                    });
                                });
                                $('#year_auto').change(function () {
                                    dataFilter(1,`{{route('gat_brands')}}?type_auto=${$('#type_auto').val()}`);
                                });
                                $('#brand_auto').change(function () {
                                    dataFilter(2,`{{route('gat_model')}}?type_auto=${$('#type_auto').val()}&brand_id=${$('#brand_auto').val()}&year_auto=${$('#year_auto').val()}`);
                                });
                                $('#model_auto').change(function () {
                                    dataFilter(3,`{{route('get_modifications')}}?type_auto=${$('#type_auto').val()}&model_id=${$('#model_auto').val()}&type_mod=Body`);
                                });
                                $('#body_auto').change(function () {
                                    dataFilter(4,`{{route('get_modifications')}}?type_auto=${$('#type_auto').val()}&model_id=${$('#model_auto').val()}&type_mod=Engine`);
                                });
                                $('#engine_auto').change(function () {
                                    dataFilter(5,`{{route('get_modifications')}}?type_auto=${$('#type_auto').val()}&model_id=${$('#model_auto').val()}&type_mod=General&body=${$('#body_auto').val()}&engine=${$('#engine_auto').val()}`);
                                });
                                $('#modification_auto').change(function () {
                                    dataFilter(6);
                                });
                            </script>
                        </div>
                    </div>
                </div>
                <div class="tab-pane  fade" id="tabBody2" role="tabpanel" aria-labelledby="tab2" aria-hidden="true" tabindex="0">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="{{route('vin_decode')}}" method="post">
                                @csrf
                                <div class="row padding-30">
                                    <div class="col-xs-12 col-sm-9 col-md-10">
                                        <input class="form-control" type="text" name="vin" placeholder="Например: JTEHT05JX02054465">
                                    </div>
                                    <div class="col-xs-12 col-sm-3 col-md-2">
                                        <button type="submit" class="btn-round btn-sm">{{__('Подобрать')}}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>


</section>
