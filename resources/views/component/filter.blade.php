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
                                <form>
                                    @csrf
                                    <div class="row padding-30">
                                        <div class="col-xs-12 col-sm-10">
                                            <input class="form-control" type="text" name="search" placeholder="поиск по коду товара, модели авто">
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
                                    <div class="col-xs-12 text-left relative padding-top-10 padding-bottom-10">
                                        <a class="link border-bottom bold" href="">{{__('Уточните данные по автомобилю  для отображения подходящих запчастей:')}}</a>
                                        <span class="hover-tooltip right">
                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                </span>
                                    </div>
                                    <div class="col-xs-12">
                                        <ul class="search-car__list">
                                            <li>
                                                <a href="">
                                                    <span>Легковой</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="">
                                                    <span>Выберите год</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="">
                                                    <span>Выберите марку</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="">
                                                    <span>Выберите модель</span>
                                                </a>
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
                        </div>
                    </div>
                </div>
                <div class="tab-pane  fade" id="tabBody2" role="tabpanel" aria-labelledby="tab2" aria-hidden="true" tabindex="0">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="{{route('vin_decode')}}" method="post">
                                @csrf
                                <div class="row padding-30">
                                    <div class="col-xs-12 text-right padding-top-10 padding-bottom-10">
                                        <a class="link border-bottom bold" href="">{{__('Помощь в подборе специалистом')}}</a>
                                        <span class="hover-tooltip">
                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                </span>
                                    </div>
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