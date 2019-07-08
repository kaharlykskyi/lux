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
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}" type="image/x-icon">
    <link rel="icon" href="{{asset('favicon.ico')}}" type="image/x-icon">

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
    <link rel="stylesheet" href="{{asset('css/selectric.css')}}">
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
    <script src="{{asset('js/jquery.selectric.min.js')}}"></script>
    <script src="{{asset('js/jquery.colorbox.js')}}"></script>
</head>
<body>
    <!-- Page Wrapper -->
    <div id="wrap" class="layout-1">
        <!-- Top bar -->
        @include('component.top_bar')

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
                        <div class="container" id="shopping-cart-block">
                            <div class="row">
                                <div class="col-sm-12 padding-top-30 padding-bottom-30 text-center">
                                    <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                                </div>
                            </div>
                        </div>
                    </section>
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
    <script type="text/javascript" src="{{asset('js/common.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/maskedinput.min.js')}}"></script>
    @yield('script')
    <script>
        $(".phone_mask").mask("(999) 999-99-99");

        const  getCountry = (obj) => {
            let word = $(obj).val();
            $( '#' + obj[0].id ).autocomplete({
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

        const getCity = (obj,element) => {
            let word = $(obj).val();
            let iso =  $( element ).val();
            iso = iso.split(' ',2);
            if(iso[1] !== undefined){
                iso = iso[1].substring(1, iso[1].length-1).split('/',2);
            }
            $( '#' + obj[0].id).autocomplete({
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
