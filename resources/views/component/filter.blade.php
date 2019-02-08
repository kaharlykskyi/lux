<section class="filter-section">

    <div class="container padding-top-20 padding-bottom-20">
        <section id="fancyTabWidget" class="tabs t-tabs">
            <ul class="nav nav-tabs fancyTabs" role="tablist">

                <li class="tab fancyTab active">
                    <div class="arrow-down"><div class="arrow-down-inner"></div></div>
                    <a id="tab0" href="#tabBody0" role="tab" aria-controls="tabBody0" aria-selected="true" data-toggle="tab" tabindex="0">
                        <span>{{__('Поиск')}}</span>
                    </a>
                    <div class="whiteBlock"></div>
                </li>

                <li class="tab fancyTab">
                    <div class="arrow-down"><div class="arrow-down-inner"></div></div>
                    <a id="tab1" href="#tabBody1" role="tab" aria-controls="tabBody1" aria-selected="true" data-toggle="tab" tabindex="0">
                        <span>{{__('Подбор по авто')}}</span>
                    </a>
                    <div class="whiteBlock"></div>
                </li>

                <li class="tab fancyTab">
                    <div class="arrow-down"><div class="arrow-down-inner"></div></div>
                    <a id="tab2" href="#tabBody2" role="tab" aria-controls="tabBody2" aria-selected="true" data-toggle="tab" tabindex="0">
                        <span>{{__('Подбор по vin')}}</span>
                    </a>
                    <div class="whiteBlock"></div>
                </li>
            </ul>
            <div id="myTabContent" class="tab-content fancyTabContent" aria-live="polite">
                <div class="tab-pane  fade active in" id="tabBody0" role="tabpanel" aria-labelledby="tab0" aria-hidden="false" tabindex="0">
                    <div>
                        <div class="row">

                            <div class="col-md-12">
                                <form action="{{route('catalog')}}" method="GET">
                                    <div class="row padding-30">
                                        <div class="col-xs-12 col-sm-10">
                                            <input class="form-control" type="text" name="search_product_article" placeholder="поиск по коду товара">
                                        </div>
                                        <div class="col-xs-12 col-sm-2">
                                            <button type="submit" class="btn-round btn-sm">{{__('Поиск')}}</button>
                                        </div>
                                        <div class="col-xs-12 text-right padding-top-10">
                                            <a class="link" href="">{{__('Вы искали')}}</a>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="tab-pane  fade" id="tabBody1" role="tabpanel" aria-labelledby="tab1" aria-hidden="true" tabindex="0">
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
                                                    <option selected value="">{{__('Выберите год')}}</option>
                                                    @for($i=(int)date('Y');$i >= 1980;$i--)
                                                        <option value="{{$i}}">{{$i}}</option>
                                                    @endfor
                                                </select>
                                            </li>
                                            <li class="brand_auto">
                                                <select class="filter_select" name="brand_auto" id="brand_auto">
                                                    <option selected value="">{{__('Выберите марку')}}</option>
                                                </select>
                                            </li>
                                            <li>
                                                <select class="filter_select" name="model_auto" id="model_auto">
                                                    <option selected value="">{{__('Выберите модель')}}</option>
                                                </select>
                                            </li>
                                            <li>
                                                <select class="filter_select" name="body_auto" id="body_auto">
                                                    <option selected value="">{{__('Выберите кузов')}}</option>
                                                </select>
                                            </li>
                                            <li>
                                                <select class="filter_select" name="engine_auto" id="engine_auto">
                                                    <option selected value="">{{__('Выберите двигатель')}}</option>
                                                </select>
                                            </li>
                                            <li>
                                                <select class="filter_select" name="modification_auto" id="modification_auto">
                                                    <option selected value="">{{__('Выберите модификацию')}}</option>
                                                </select>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-xs-12 text-right padding-top-10">
                                        @auth
                                            <a class="link margin-right-10" href="">{{__('Мои автомобили')}}</a>
                                        @endauth
                                        <a class="link" href="" onclick="return false;" data-toggle="modal" data-target="#search_cars_modal">{{__('Просмотреные авто')}}</a>
                                    </div>
                                </div>
                                <div class="row margin-top-10 hidden" id="search-detail-car">
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
                                    dataFilter(1);
                                });
                                $('#brand_auto').change(function () {
                                    dataFilter(2);
                                });
                                $('#model_auto').change(function () {
                                    dataFilter(3);
                                });
                                $('#body_auto').change(function () {
                                    dataFilter(4);
                                });
                                $('#engine_auto').change(function () {
                                    dataFilter(5);
                                });
                                $('#modification_auto').change(function () {
                                    dataFilter(6);
                                });

                                function dataFilter(level) {
                                    switch (level) {
                                        case 1:
                                            getDateFilter(`{{route('gat_brands')}}?type_auto=${$('#type_auto').val()}`,'Выберите марку','#brand_auto',['id','description']);
                                            if ($('#year_auto').val() === ''){
                                                $('#brand_auto').next().prop('disabled', 'disabled').selectric('refresh');
                                            }
                                            break;
                                        case 2:
                                            getDateFilter(`{{route('gat_model')}}?type_auto=${$('#type_auto').val()}&brand_id=${$('#brand_auto').val()}&year_auto=${$('#year_auto').val()}`,'Выберите модель','#model_auto',['id','name']);
                                            if ($('#brand_auto').val() === ''){
                                                $('#model_auto').prop('disabled', 'disabled').selectric('refresh');
                                            }
                                            break;
                                        case 3:
                                            getDateFilter(`{{route('get_modifications')}}?type_auto=${$('#type_auto').val()}&model_id=${$('#model_auto').val()}&type_mod=Body`,'Выберите кузов','#body_auto',['displayvalue','displayvalue']);
                                            if ($('#model_auto').val()){
                                                $('#body_auto').prop('disabled', 'disabled').selectric('refresh');
                                            }
                                            break;
                                        case 4:
                                            getDateFilter(`{{route('get_modifications')}}?type_auto=${$('#type_auto').val()}&model_id=${$('#model_auto').val()}&type_mod=Engine`,'Выберите двигатель','#engine_auto',['displayvalue','displayvalue']);
                                            if ( $('#body_auto').val() !== ''){
                                                $('#engine_auto').prop('disabled', 'disabled').selectric('refresh');
                                            }
                                            break;
                                        case 5:
                                            getDateFilter(`{{route('get_modifications')}}?type_auto=${$('#type_auto').val()}&model_id=${$('#model_auto').val()}&type_mod=General`,'Выберите модификацию','#modification_auto',['id','name']);
                                            if ($('#engine_auto').val() !== ''){
                                                $('#modification_auto').prop('disabled', 'disabled').selectric('refresh');
                                            }
                                            break;
                                        case 6:
                                            if($('#modification_auto').val() !== '' && !$('#modification_auto').hasAttribute('disabled')){
                                                $('#search-detail-car').removeClass('hidden');
                                            }
                                            break;
                                        default:
                                            $('.search-car__list').children('li:not(:first-child):not(:nth-child(2))').find('select').prop('disabled', 'disabled').selectric('refresh');
                                    }
                                }

                                function getDateFilter(link,mass,obj,dataKey) {
                                    $.get(link, function(data) {
                                        let str_data = `<option selected value="">${mass}</option>`;
                                        data.response.forEach(function (item) {
                                            str_data += `<option value="${item[dataKey[0]]}">${item[dataKey[1]]}</option>`
                                        });
                                        $(obj).removeAttr('disabled').html(str_data).selectric('refresh');
                                    });
                                }
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
                                    <div class="col-xs-12 col-sm-10">
                                        <input class="form-control" type="text" name="vin" placeholder="Например: JTEHT05JX02054465">
                                    </div>
                                    <div class="col-xs-12 col-sm-2">
                                        <button type="submit" class="btn-round btn-sm">{{__('Подобрать')}}</button>
                                    </div>
                                    <div class="col-xs-12 text-right padding-top-10">
                                        <a class="link" href="">{{__('Просмотреные авто')}}</a>
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