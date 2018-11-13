<!DOCTYPE html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="content-type" content="text/html;" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{asset('images/favicon.ico')}}" type="image/x-icon">
    <link rel="icon" href="{{asset('images/favicon.ico')}}" type="image/x-icon">

    <!-- SLIDER REVOLUTION 4.x CSS SETTINGS -->
    <link rel="stylesheet" type="text/css" href="{{asset('rs-plugin/css/settings.css')}}" media="screen" />

    <!-- StyleSheets -->
    <link rel="stylesheet" href="{{asset('css/ionicons.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/main.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/responsive.css')}}">
    <link rel="stylesheet" href="{{asset('css/custom-style.css')}}">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />

    <!-- Fonts Online -->
    <link href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i" rel="stylesheet">


    <!-- JavaScripts -->
    <script src="{{asset('js/vendors/modernizr.js')}}"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="{{asset('js/vendors/jquery/jquery.min.js')}}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body>
    <!-- Page Wrapper -->
    <div id="wrap" class="layout-1">
        <!-- Top bar -->
        @component('component.top_bar')

        @endcomponent

        <!-- Header -->
        @component('component.header')

        @endcomponent

        <!-- Content -->
        @yield('content')
        <!-- End Content -->

        <!-- Footer -->
        @component('component.footer')

        @endcomponent
        <!-- End Footer -->
    </div>

    <!-- CART-->
    <div class="modal fade shopping-cart bs-example-modal-lg" id="cart" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{__('Корзина заказа')}}</h4>
                </div>
                <div class="modal-body">
                    <section class="shopping-cart padding-bottom-30">
                        <div class="container table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Товар</th>
                                    <th class="text-center">Цена</th>
                                    <th class="text-center">Количество</th>
                                    <th class="text-center">Общая цена</th>
                                    <th>&nbsp; </th>
                                </tr>
                                </thead>
                                <tbody>

                                <!-- Item Cart -->
                                <tr>
                                    <td><div class="media">
                                            <div class="media-left"> <a href="#."> <img class="img-responsive" src="images/item-img-1-1.jpg" alt="" > </a> </div>
                                            <div class="media-body">
                                                <p>E-book Reader Lector De Libros
                                                    Digitales 7''</p>
                                            </div>
                                        </div></td>
                                    <td class="text-center padding-top-60">$200.00</td>
                                    <td class="text-center"><!-- Quinty -->

                                        <div class="quinty padding-top-20">
                                            <input type="number" value="02">
                                        </div></td>
                                    <td class="text-center padding-top-60">$400.00</td>
                                    <td class="text-center padding-top-60"><a href="#." class="remove"><i class="fa fa-close"></i></a></td>
                                </tr>

                                <!-- Item Cart -->
                                <tr>
                                    <td><div class="media">
                                            <div class="media-left"> <a href="#."> <img class="img-responsive" src="images/item-img-1-2.jpg" alt="" > </a> </div>
                                            <div class="media-body">
                                                <p>E-book Reader Lector De Libros
                                                    Digitales 7''</p>
                                            </div>
                                        </div></td>
                                    <td class="text-center padding-top-60">$200.00</td>
                                    <td class="text-center"><div class="quinty padding-top-20">
                                            <input type="number" value="02">
                                        </div></td>
                                    <td class="text-center padding-top-60">$400.00</td>
                                    <td class="text-center padding-top-60"><a href="#." class="remove"><i class="fa fa-close"></i></a></td>
                                </tr>
                                </tbody>
                            </table>

                            <!-- Promotion -->
                            <div class="promo">
                                <div class="coupen">
                                    <label> Promotion Code
                                        <input type="text" placeholder="Your code here">
                                        <button type="submit"><i class="fa fa-arrow-circle-right"></i></button>
                                    </label>
                                </div>

                                <!-- Grand total -->
                                <div class="g-totel">
                                    <h5>Grand total: <span>$500.00</span></h5>
                                </div>
                            </div>

                            <!-- Button -->
                            <div class="pro-btn">
                                <a href="#." class="btn-round btn-light" data-dismiss="modal">{{__('Продолжить покупки')}}</a>
                                <a href="#." class="btn-round">Go Payment Methods</a>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="text-center block h4">{{__('Рекомендуем также обратить внимание')}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScripts -->
    <script src="{{asset('js/vendors/wow.min.js')}}"></script>
    <script src="{{asset('js/vendors/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/vendors/own-menu.js')}}"></script>
    <script src="{{asset('js/vendors/jquery.sticky.js')}}"></script>
    <script src="{{asset('js/vendors/owl.carousel.min.js')}}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>

    <!-- SLIDER REVOLUTION 4.x SCRIPTS  -->
    <script type="text/javascript" src="{{asset('rs-plugin/js/jquery.tp.t.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('rs-plugin/js/jquery.tp.min.js')}}"></script>
    <script src="{{asset('js/main.js')}}"></script>
    <script>
        const  getCountry = (obj) => {
            let word = $(obj).val();
            $( "#country" ).autocomplete({
                source: (request, response) => {
                    $('.country .loader').css({display: 'inline-block'});
                    $.ajax({
                        url: `http://geohelper.info/api/v1/countries?locale%5Blang%5D=ru&locale%5BfallbackLang%5D=en&filter[name]=${word}&apiKey={{config('app.geo_key')}}`,
                        type: 'GET',
                        success: (data) => {
                            response($.map(data.result, (item) => {
                                return{
                                    value: item.name + ` (${item.iso}/${item.iso3})`,
                                }
                            }));
                            $('.country .loader').css({display: 'none'});
                        }
                    });
                },
                minLength: 1
            });
        };

        const getCity = (obj) => {
            let word = $(obj).val();
            let iso =  $( "#country" ).val();
            iso = iso.split(' ',2);
            iso = iso[1].substring(1, iso[1].length-1).split('/',2);
            $( "#city" ).autocomplete({
                source: (request, response) => {
                    $('.city .loader').css({display: 'inline-block'});
                    $.ajax({
                        url: `http://geohelper.info/api/v1/cities?locale%5Blang%5D=ru&locale%5BfallbackLang%5D=en&filter[name]=${word}&filter[countryIso]=${iso[0].toLowerCase()}&apiKey={{config('app.geo_key')}}`,
                        type: 'GET',
                        success: (data) => {
                            response($.map(data.result, (item) => {
                                return{
                                    value: item.name,
                                }
                            }));
                            $('.city .loader').css({display: 'none'});
                        }
                    });
                },
                minLength: 1
            });
        };


    </script>
</body>
</html>
