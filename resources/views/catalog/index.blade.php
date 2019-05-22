@extends('layouts.app')

@section('content')

    <div id="content">
        <!-- Linking -->
        @component('component.breadcrumb',[
            'links' => [
                (object)['title' => 'Каталог запчастей']
            ]
        ])
        @endcomponent

    <!-- Products -->
        <section class="padding-top-40 padding-bottom-60">
            <div class="container">
                <div class="row">

                    <!-- Shop Side Bar -->
                    <div class="col-md-3">
                        @if(!empty($catalog_products) && $catalog_products->total() > 0)
                            @component('catalog.compenents.filter',[
                                'brands' => $brands,
                                'min_price' => $min_price,
                                'max_price' => $max_price,
                                'attributes' => $attribute,
                                'filter_supplier' => $filter_supplier
                            ])@endcomponent
                        @endif
                    </div>

                    <!-- Products -->
                    <div class="col-md-9">
                        @if(isset($list_product) || isset($list_catalog))
                            @include('catalog.partirals.list_product')
                        @else
                           @include('catalog.partirals.grid_product')
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        function addCart(hurl) {
            $.post(hurl,{'product_count':1,'_token':'{{csrf_token()}}'}, function (data) {
                console.log(data.response);
                $('#total-price').text(`${data.response.sum} грн`);
                $('#count-product-mini-cart').text(data.response.count);
                alert(data.response.save);
            });
        }
    </script>

@endsection
