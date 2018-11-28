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
                        <li class="col-sm-12">
                            <label>{{ __('Марка') }}
                                <select class="form-control selectpicker" data-live-search="true" name="mark" required>
                                    <option label="" value="0"></option>
                                    <option label="ACURA" value="1213">ACURA</option>
                                    <option label="ALFA ROMEO" value="502">ALFA ROMEO</option>
                                    <option label="APRILIA MOTO" value="11546">APRILIA MOTO</option>
                                    <option label="AUDI" value="504">AUDI</option>
                                    <option label="BENELLI MOTO" value="11547">BENELLI MOTO</option>
                                    <option label="BMW" value="511">BMW</option>
                                    <option label="BMW MOTO" value="11578">BMW MOTO</option>
                                    <option label="BMW Mini" value="1231">BMW Mini</option>
                                    <option label="BUELL MOTO" value="11545">BUELL MOTO</option>
                                    <option label="BYD" value="10624">BYD</option>
                                    <option label="CADILLAC" value="852">CADILLAC</option>
                                    <option label="CAGIVA MOTO" value="11548">CAGIVA MOTO</option>
                                    <option label="CHERY" value="10389">CHERY</option>
                                    <option label="CHEVROLET" value="602">CHEVROLET</option>
                                    <option label="CHRYSLER" value="513">CHRYSLER</option>
                                    <option label="CITROEN" value="514">CITROEN</option>
                                    <option label="DACIA" value="603">DACIA</option>
                                    <option label="DAEWOO" value="649">DAEWOO</option>
                                    <option label="DAF" value="516">DAF</option>
                                    <option label="DAIHATSU" value="517">DAIHATSU</option>
                                    <option label="DODGE" value="521">DODGE</option>
                                    <option label="DUCATI MOTO" value="11550">DUCATI MOTO</option>
                                    <option label="FIAT" value="524">FIAT</option>
                                    <option label="FORD" value="525">FORD</option>
                                    <option label="FORD USA" value="814">FORD USA</option>
                                    <option label="GEELY" value="10091">GEELY</option>
                                    <option label="GENERAL MOTORS" value="792">GENERAL MOTORS</option>
                                    <option label="GREAT WALL" value="10405">GREAT WALL</option>
                                    <option label="HONDA" value="533">HONDA</option>
                                    <option label="HONDA MOTO" value="11579">HONDA MOTO</option>
                                    <option label="HUMMER" value="1214">HUMMER</option>
                                    <option label="HUSQVARNA MOTO" value="11738">HUSQVARNA MOTO</option>
                                    <option label="HYOSUNG MOTO" value="11553">HYOSUNG MOTO</option>
                                    <option label="HYUNDAI" value="647">HYUNDAI</option>
                                    <option label="INFINITI" value="1234">INFINITI</option>
                                    <option label="ISUZU" value="538">ISUZU</option>
                                    <option label="IVECO" value="539">IVECO</option>
                                    <option label="JAGUAR" value="540">JAGUAR</option>
                                    <option label="JAWA MOTO" value="11571">JAWA MOTO</option>
                                    <option label="JEEP" value="910">JEEP</option>
                                    <option label="KAWASAKI MOTO" value="11554">KAWASAKI MOTO</option>
                                    <option label="KIA" value="648">KIA</option>
                                    <option label="KTM MOTO" value="11555">KTM MOTO</option>
                                    <option label="LADA" value="545">LADA</option>
                                    <option label="LANCIA (FIAT)" value="546">LANCIA (FIAT)</option>
                                    <option label="LAND ROVER" value="1292">LAND ROVER</option>
                                    <option label="LEXUS" value="874">LEXUS</option>
                                    <option label="MAN" value="551">MAN</option>
                                    <option label="MAZ" value="298">MAZ</option>
                                    <option label="MAZDA" value="552">MAZDA</option>
                                    <option label="MERCEDES" value="553">MERCEDES</option>
                                    <option label="MG ROVER" value="554">MG ROVER</option>
                                    <option label="MITSUBISHI" value="555">MITSUBISHI</option>
                                    <option label="NEOPLAN" value="626">NEOPLAN</option>
                                    <option label="NISSAN" value="558">NISSAN</option>
                                    <option label="OPEL" value="561">OPEL</option>
                                    <option label="PEUGEOT" value="563">PEUGEOT</option>
                                    <option label="PORSCHE" value="565">PORSCHE</option>
                                    <option label="RENAULT" value="566">RENAULT</option>
                                    <option label="RENAULT TRUCKS" value="739">RENAULT TRUCKS</option>
                                    <option label="ROVER" value="568">ROVER</option>
                                    <option label="SATURN" value="1205">SATURN</option>
                                    <option label="SCANIA" value="572">SCANIA</option>
                                    <option label="SEAT" value="573">SEAT</option>
                                    <option label="SKODA" value="575">SKODA</option>
                                    <option label="SMART" value="1149">SMART</option>
                                    <option label="SSANG YONG" value="639">SSANG YONG</option>
                                    <option label="SUBARU" value="576">SUBARU</option>
                                    <option label="SUZUKI" value="577">SUZUKI</option>
                                    <option label="SUZUKI MOTO" value="11582">SUZUKI MOTO</option>
                                    <option label="TOYOTA" value="579">TOYOTA</option>
                                    <option label="TRIUMPH MOTO" value="11583">TRIUMPH MOTO</option>
                                    <option label="VOLVO" value="586">VOLVO</option>
                                    <option label="VOLKSWAGEN" value="587">VOLKSWAGEN</option>
                                    <option label="YAMAHA MOTO" value="11565">YAMAHA MOTO</option>
                                </select>
                            </label>
                        </li>
                        <li class="col-sm-12">
                            <label>{{ __('Год выпуска') }}
                                <select class="form-control selectpicker" data-live-search="true" name="year" required>
                                    <option label="" value="0"></option>
                                    @for($i = (integer)date('Y'); $i > 1970; $i--)
                                        <option label="{{$i}}" value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </label>
                        </li>
                        <li class="col-sm-12">
                            <label>{{ __('Модель') }}
                                <input type="text" class="form-control" name="model" value="">
                            </label>
                        </li>
                        <li class="col-sm-12">
                            <label>{{ __('Обьем двигателя') }}
                                <input type="text" class="form-control" name="v_motor" value="">
                            </label>
                        </li>
                        <li class="col-sm-12">
                            <label>{{ __('Тип двигателя') }}
                                <select class="form-control" name="type_motor" required>
                                    <option label="" value="0"></option>
                                    <option label="Бензин" value="1">Бензин</option>
                                    <option label="Дизел" value="2">Дизел</option>
                                    <option label="Газ" value="3">Газ</option>
                                </select>
                            </label>
                        </li>
                        <li class="col-sm-12 error-response"></li>
                        <li class="col-sm-12 text-left">
                            <button type="submit" class="btn-round">{{__('Добавить')}}</button>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
    </div>
</div>