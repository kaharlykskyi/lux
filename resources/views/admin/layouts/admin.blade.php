<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">

    <!-- Title Page-->
    <title>Dashboard</title>

    <!-- Fontfaces CSS-->
    <link href="{{asset('admin_area/css/font-face.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('admin_area/vendor/font-awesome-4.7/css/font-awesome.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('admin_area/vendor/font-awesome-5/css/fontawesome-all.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('admin_area/vendor/mdi-font/css/material-design-iconic-font.min.css')}}" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="{{asset('admin_area/vendor/bootstrap-4.1/bootstrap.min.css')}}" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="{{asset('admin_area/vendor/animsition/animsition.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('admin_area/vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('admin_area/vendor/wow/animate.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('admin_area/vendor/css-hamburgers/hamburgers.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('admin_area/vendor/slick/slick.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('admin_area/vendor/select2/select2.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('admin_area/vendor/perfect-scrollbar/perfect-scrollbar.css')}}" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="{{asset('admin_area/css/theme.css')}}" rel="stylesheet" media="all">

    <!-- Jquery JS-->
    <script src="{{asset('admin_area/vendor/jquery-3.2.1.min.js')}}"></script>
    <script src="//cdn.ckeditor.com/4.11.3/full/ckeditor.js"></script>
    @yield('style')
</head>

<body {{--class="animsition"--}}> <!--TODO:uncomment on finished dev-->
<div class="page-wrapper">
    <!-- HEADER MOBILE-->
    @component('admin.component.header_mob')@endcomponent
    <!-- END HEADER MOBILE-->

    <!-- MENU SIDEBAR-->
    @component('admin.component.sidebar')@endcomponent
    <!-- END MENU SIDEBAR-->

    <!-- PAGE CONTAINER-->
    <div class="page-container">
        <!-- HEADER DESKTOP-->
        @component('admin.component.header_desc')@endcomponent
        <!-- HEADER DESKTOP-->

        <!-- MAIN CONTENT-->
        @yield('content')
        <!-- END MAIN CONTENT-->
        <!-- END PAGE CONTAINER-->
    </div>

</div>

<!-- Bootstrap JS-->
<script src="{{asset('admin_area/vendor/bootstrap-4.1/popper.min.js')}}"></script>
<script src="{{asset('admin_area/vendor/bootstrap-4.1/bootstrap.min.js')}}"></script>
<!-- Vendor JS       -->
<script src="{{asset('admin_area/vendor/slick/slick.min.js')}}">
</script>
<script src="{{asset('admin_area/vendor/wow/wow.min.js')}}"></script>
<script src="{{asset('admin_area/vendor/animsition/animsition.min.js')}}"></script>
<script src="{{asset('admin_area/vendor/bootstrap-progressbar/bootstrap-progressbar.min.js')}}">
</script>
<script src="{{asset('admin_area/vendor/counter-up/jquery.waypoints.min.js')}}"></script>
<script src="{{asset('admin_area/vendor/counter-up/jquery.counterup.min.js')}}">
</script>
<script src="{{asset('admin_area/vendor/circle-progress/circle-progress.min.js')}}"></script>
<script src="{{asset('admin_area/vendor/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
<script src="{{asset('admin_area/vendor/chartjs/Chart.bundle.min.js')}}"></script>
<script src="{{asset('admin_area/vendor/select2/select2.min.js')}}"></script>
@yield('script')

<!-- Main JS-->
<script src="{{asset('admin_area/js/main.js')}}"></script>
@include('admin.dashboard.component.stat_chart')
<script>
    function orderStatus(id,obj) {
        $.get(`{{route('admin.product.change_status_order')}}?orderID=${id}&statusID=${$(obj).val()}`,function (data) {
            alert(data.response);
        });
    }
    function saveInvoice(id,obj) {
        if ($(obj).val() !== ''){
            $.get(`{{route('admin.product.change_status_order')}}?orderID=${id}&invoice=${$(obj).val()}`,function (data) {
                alert(data.response);
            });
        }
    }
    function setPosition(id) {
        const client_val = document.getElementById(id).getBoundingClientRect();
        $(`div[data-id="${id}"]`).css({
            top:`${client_val.y + 20}px`,
            left: `${client_val.x + 30}px`,
        }).show();
    }
</script>
</body>

</html>
<!-- end document-->
