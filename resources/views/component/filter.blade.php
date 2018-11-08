<section class="filter-section">
    <div class="container">
        <div class="row padding-top-20 padding-bottom-20">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active text-center">
                    <a class="text-uppercase" href="#search-filter" aria-controls="home" role="tab" data-toggle="tab">
                        {{__('Поиск')}}
                    </a>
                </li>
                <li role="presentation" class="text-center">
                    <a class="text-uppercase" href="#search-car" aria-controls="profile" role="tab" data-toggle="tab">
                        {{__('Подбор по авто')}}
                    </a>
                </li>
                <li role="presentation" class="text-center">
                    <a class="text-uppercase" href="#search-vin" aria-controls="messages" role="tab" data-toggle="tab">
                        {{__('Подбор по vin')}}
                    </a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="search-filter">
                    <form>
                        @csrf
                        <div class="row padding-30">
                            <div class="col-xs-12 col-sm-10">
                                 <input class="form-control" type="text" name="search" placeholder="поиск по коду товара, модели авто">
                            </div>
                            <div class="col-xs-12 col-sm-2">
                                <button type="submit" class="btn-round">{{__('Поиск')}}</button>
                            </div>
                            <div class="col-xs-12 text-right padding-top-10">
                                <a class="link" href="">{{__('Вы искали')}}</a>
                            </div>
                        </div>
                    </form>
                </div>
                <div role="tabpanel" class="tab-pane" id="search-car">
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
                <div role="tabpanel" class="tab-pane" id="search-vin">
                    <form>
                        @csrf
                        <div class="row padding-30">
                            <div class="col-xs-12 text-right padding-top-10 padding-bottom-10">
                                <a class="link border-bottom bold" href="">{{__('Помощь в подборе специалистом')}}</a>
                                <span class="hover-tooltip">
                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                </span>
                            </div>
                            <div class="col-xs-12 col-sm-10">
                                <input class="form-control" type="text" name="search" placeholder="Например: JTEHT05JX02054465">
                            </div>
                            <div class="col-xs-12 col-sm-2">
                                <button type="submit" class="btn-round">{{__('Подобрать')}}</button>
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