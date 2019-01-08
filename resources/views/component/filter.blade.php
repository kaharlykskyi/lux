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
                            <form>
                                @csrf
                                <div class="row padding-30">
                                    <div class="col-xs-12">
                                        <ul class="search-car__list">
                                            <li>
                                                <select class="filter_select" name="type_auto" id="type_auto">
                                                    <option selected value="passenger">{{__('Легковой')}}</option>
                                                    <option value="commercial">{{__('Грузовой')}}</option>
                                                </select>
                                            </li>
                                            <li>
                                                <select class="filter_select" name="year_auto" id="year_auto">
                                                    <option selected value="">{{__('Выберите год')}}</option>
                                                    @for($i=(int)date('Y');$i >= 1980;$i--)
                                                        <option value="{{$i}}">{{$i}}</option>
                                                    @endfor
                                                </select>
                                            </li>
                                            <li>
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
                                                <a href="">
                                                    <span>Выберите двигатель</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="">
                                                    <span>Выберите кузов</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="">
                                                    <span>Выберите модификацию</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-xs-12 text-right padding-top-10">
                                        <a class="link" href="">{{__('Просмотреные авто')}}</a>
                                    </div>
                                </div>
                            </form>
                            <script>
                                $(function() {
                                    $('select.filter_select').selectric();
                                    $(dataFilter);
                                });
                                $('#year_auto').change(dataFilter);
                                $('#brand_auto').change(dataFilter);

                                function dataFilter() {
                                    if ($('#year_auto').val() !== ''){
                                        $.get(`{{route('gat_brands')}}?type_auto=${$('#type_auto').val()}`, function(data) {
                                            let str_data = '';
                                            data.response.forEach(function (item) {
                                                str_data += `<option value="${item.id}">${item.description}</option>`
                                            });
                                            $('#brand_auto').removeAttr('disabled').html(str_data).selectric('refresh');
                                        });

                                    } else{
                                        $('#brand_auto').prop('disabled', 'disabled').selectric('refresh');
                                    }

                                    if ($('#brand_auto').val() !== '' && $('#year_auto').val() !== ''){
                                        $.get(`{{route('gat_model')}}?type_auto=${$('#type_auto').val()}&brand_id=${$('#brand_auto').val()}&year_auto=${$('#year_auto').val()}`, function(data) {

                                            console.log(data.response);
                                            let str_data = '';
                                            data.response.forEach(function (item) {
                                                str_data += `<option value="${item.id}">${item.name}</option>`
                                            });
                                            $('#model_auto').removeAttr('disabled').html(str_data).selectric('refresh');
                                        });
                                    } else {
                                        $('#model_auto').prop('disabled', 'disabled').selectric('refresh');
                                    }
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