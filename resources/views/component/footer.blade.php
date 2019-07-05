<footer style="background: #3134393d">
    <div class="container">

        <!-- Footer Upside Links -->
        <div class="foot-link">
        </div>
        <div class="row">

            <!-- Contact -->
            <div class="col-md-4">
                <h4>Контакты {{config('app.name')}}!</h4>
                <p>
                    {{__('Телефон')}}:<span style="display: flex;flex-direction: column;margin-left: 60px;"><a href="tel:{{config('app.company_phone')}}">{{config('app.company_phone')}}</a>
                    <a href="tel:380933401041">{{__('+38(093)340-10-41')}}</a>
                    <a href="tel:380687081515">{{__('+38(068)708-15-15')}}</a></span>
                </p>
                <p>
                    {{__('Адресс')}}:
                    {{config('app.company_location')}}<br>
                    <a href="https://goo.gl/maps/m8LwKwkbNf7MpBPFA" target="_blank">(посмотреть на карте)</a>
                </p>
                <p>{{__('E-mail')}}: {{config('app.work_mail')}}</p>
                <div class="social-links"> <a href="#."><i class="fa fa-facebook"></i></a> <a href="#."><i class="fa fa-twitter"></i></a> <a href="#."><i class="fa fa-linkedin"></i></a> <a href="#."><i class="fa fa-pinterest"></i></a> <a href="#."><i class="fa fa-instagram"></i></a> <a href="#."><i class="fa fa-google"></i></a> </div>
            </div>
            <div class="col-md-4">
                <h4>{{__('Для клиента')}}</h4>
                <ul class="links-footer">
                    @isset($pages_global)
                        @foreach($pages_global as $page)
                            @if($page->footer_column === 1)
                                <li><a href="{{route('page',$page->alias)}}">{{$page->title}}</a></li>
                            @endif
                        @endforeach
                    @endisset
                </ul>
            </div>
            <div class="col-md-4">
                <h4>{{__('Информация')}}</h4>
                <ul class="links-footer">
                    @isset($pages_global)
                        @foreach($pages_global as $page)
                            @if($page->footer_column === 2)
                                <li><a href="{{route('page',$page->alias)}}">{{$page->title}}</a></li>
                            @endif
                        @endforeach
                    @endisset
                </ul>
            </div>
            <div class="col-md-8 col-md-offset-4 footer-city">
                <p>Киев, Харьков, Днепр, Одесса, Львів, Запорожье, Винница, Житомир, Ивано-Франковск, Кропивницкий, Луцк, Николаев, Полтава, Ровно, Сумы, Тернополь, Ужгород, Херсон, Хмельницкий, Черкассы, Чернигов, Черновцы, Мариуполь, Каменское, Кременчуг, Белая Церковь, Краматорск, Мелитополь, Никополь, Славянск, Бердянск, Павлоград, Северодонецк, Лисичанск, Каменец-Подольский </p>
            </div>
        </div>
    </div>
</footer>

<!-- Rights -->
<div class="rights" style="background: #31343952">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <p>Copyright © {{date('Y')}} <a href="#." class="ri-li"> {{config('app.name')}} </a>. Все права защищены</p>
            </div>
            <div class="col-sm-6 text-right"> <img src="{{asset('images/card-icon.png')}}" alt=""> </div>
        </div>
    </div>
</div>

<!-- GO TO TOP  -->
<a href="#" class="cd-top"><i class="fa fa-angle-up"></i></a>
<!-- GO TO TOP End -->

{{--ODER CALL--}}
<div type="button" class="callback-bt" data-toggle="modal" data-target="#callOder">
    <div class="text-call">
        <i class="fa fa-phone"></i>
        <span>Заказать<br>звонок</span>
    </div>
</div>

<div class="modal fade" id="callOder" tabindex="-1" role="dialog" aria-labelledby="callOderLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="callOderLabel">Заказать звонок</h4>
            </div>
            <div class="modal-body">
                <form role="form" action="" method="post" id="call-oder-form">
                    @csrf
                    <div class="form-group">
                        <label for="call-oder-name">Имя</label>
                        <input type="text" class="form-control" name="name" id="call-oder-name" required>
                    </div>
                    <div class="form-group">
                        <label for="call-oder-phone">Телефон</label>
                        <input type="tel" class="form-control phone_mask" name="phone" id="call-oder-phone" required>
                    </div>
                    <button type="submit" class="btn btn-round">Заказать</button><i style="display: none;margin-left: 5px;" class="fa fa-spinner fa-spin" aria-hidden="true"></i>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#call-oder-form').submit(function (e) {
            e.preventDefault();
            $('.fa-spinner.fa-spin').show();
            $.post('{{route('call_order')}}',$(this).serialize(),function (data) {
                if (data.error !== undefined){
                    let error_mass = '';
                    for (let prop in data.error){
                        error_mass += data.error[prop][0] + "\n";
                    }
                    alert(error_mass);
                } else {
                    alert(data);
                    $(this).trigger("reset");
                    $('#callOder').modal('hide')
                }
                $('.fa-spinner.fa-spin').hide();
            });
        });
    });
</script>
