@extends('admin.layouts.admin')

@section('style')
    <style type="text/css">
        /* стили блока аккордеон */
        .accordion {
            width:100%;
            margin: 0 auto
        }

        .accordion .accordion_item {
            margin-bottom:1px;
            position:relative
        }

        .accordion .title_block {
            font-weight: 400;
            font-size: 18px;
            color: #eee;
            cursor:pointer;
            background: #009688;
            padding:10px 55px 10px 15px;
            -webkit-transition:all .2s linear;
            -webkit-transition-delay:.2s;
            transition:all .2s linear
        }

        .accordion .title_block:before {
            content:'';
            height:8px;
            width:8px;
            display:block;
            border:2px solid #fefefe;
            border-right-width:0;
            border-top-width:0;
            -ms-transform:rotate(-45deg);
            -webkit-transform:rotate(-45deg);
            transform:rotate(-45deg);
            position:absolute;
            right:20px;
            top:18px
        }

        .accordion .active_block .title_block:before {
            border:2px solid #fefefe;
            border-left-width:0;
            border-bottom-width:0;
            top:18px
        }
        .accordion .title_block:hover {
            background: #26A69A
        }


        .accordion .active_block .title_block {
            background: #26A69A;
            color:#fefefe
        }

        .accordion .info {
            display:none;
            padding:10px 15px;
            overflow: hidden;
            background:#f7f7f7
        }

        .accordion .info img {
            height: auto;
            box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid m-t-75">
        @if (session('status'))
            <div class="row">
                <div class="col-sm-12">
                    <div class="alert alert-info" role="alert">
                        {{ session('status') }}
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-12 m-b-15 m-t-15">
                <a href="{{route('admin.dashboard')}}" class="btn btn-success">{{__('Назад')}}</a>
            </div>
            <div class="col-12">
                <div class="card m-t-15">
                    <div class="card-header">
                        <strong class="card-title">{{__('Бренды автомобилей для магазина')}}</strong>
                    </div>
                    <form action="{{route('admin.show_brand')}}" method="post">
                        <div class="card-body">
                            @csrf
                            <div class="accordion">
                                <section class="accordion_item">
                                    <h3 class="title_block">{{__('Бренды легковых авто')}}</h3>
                                    <div class="info">
                                        <div class="table-responsive table--no-card ">
                                            <table class="table table-borderless table-striped table-earning">
                                                <thead>
                                                <tr>
                                                    <th>{{__('Использовать')}}</th>
                                                    <th>{{__('Название')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    @isset($passenger_brands)
                                                        @foreach($passenger_brands as $brand)
                                                            <tr>
                                                                <td>
                                                                    <input @isset($passenger_brands_show) @foreach($passenger_brands_show as $item) @if((int)$item->brand_id === (int)$brand->id) checked @endif @endforeach @endisset name="passenger_{{$brand->id}}_{{$brand->matchcode}}" type="checkbox" value="{{$brand->description}}">
                                                                </td>
                                                                <td>{{$brand->description}}</td>
                                                            </tr>
                                                        @endforeach
                                                    @endisset
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </section>
                                <section class="accordion_item">
                                    <h3 class="title_block">{{__('Бренды грузовых авто')}}</h3>
                                    <div class="info">
                                        <div class="table-responsive table--no-card ">
                                            <table class="table table-borderless table-striped table-earning">
                                                <thead>
                                                <tr>
                                                    <th>{{__('Использовать')}}</th>
                                                    <th>{{__('Название')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @isset($commercial_brands)
                                                    @foreach($commercial_brands as $brand)
                                                        <tr>
                                                            <td>
                                                                <input @isset($commercial_brands_show) @foreach($commercial_brands_show as $item) @if((int)$item->brand_id === (int)$brand->id) checked @endif @endforeach @endisset name="commercial_{{$brand->id}}_{{$brand->matchcode}}" type="checkbox" value="{{$brand->description}}">
                                                            </td>
                                                            <td>{{$brand->description}}</td>
                                                        </tr>
                                                    @endforeach
                                                @endisset
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">{{__('Сохранить')}}</button>
                        </div>
                    </form>
                </div>
                <script type="text/javascript">
                    ! function(i) {
                        var o, n;
                        i(".title_block").on("click", function() {
                            o = i(this).parents(".accordion_item"), n = o.find(".info"),
                                o.hasClass("active_block") ? (o.removeClass("active_block"),
                                    n.slideUp()) : (o.addClass("active_block"), n.stop(!0, !0).slideDown(),
                                    o.siblings(".active_block").removeClass("active_block").children(
                                        ".info").stop(!0, !0).slideUp())
                        })
                    }(jQuery);
                </script>
            </div>
        </div>
        @component('admin.component.footer')@endcomponent
    </div>

@endsection
