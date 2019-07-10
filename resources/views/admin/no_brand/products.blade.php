@extends('admin.layouts.admin')

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
            <div class="col-md-12">
                <!-- DATA TABLE -->
                <h3 class="title-5 m-b-35 m-t-15">{{__('Несоотвествия бренда')}}</h3>
                <div class="table-responsive table-responsive-data2">
                    <table class="table table-data2">
                        <thead>
                        <tr>
                            <th>{{__('Бренд')}}</th>
                            <th>{{__('Кол. товаров')}}</th>
                            <th>{{__('Синоним')}}</th>
                            <th>{{__('Бренд')}}</th>
                            <th>{{__('Удалить')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @isset($no_brands)
                            @forelse($no_brands as $no_brand)
                                <tr class="tr-shadow">
                                    <td>{{$no_brand->brand}}</td>
                                    <td>
                                        <span class="block-email">{{$no_brand->count_product}}</span>
                                    </td>
                                    <td>
                                        <form action="{{route('admin.no_brands.create_replace')}}" method="post">
                                            @csrf
                                            <input style="border-bottom: 1px solid" type="text" value="{{$no_brand->brand}}" id="alias_brand" name="alias_brand" placeholder="синоним">
                                            <select id="suppliers_tecdoc" name="suppliers_tecdoc">
                                                @foreach($suppliers as $supplier)
                                                    <option value="{{$supplier->description}}#{{$supplier->id}}">{{$supplier->description}}</option>
                                                @endforeach
                                            </select>
                                            <button style="padding: 0 5px;font-size: 13px;" class="btn btn-success small">
                                                создать синоним
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="{{route('admin.no_brands.create_brand')}}" method="post">
                                            @csrf
                                            <input type="hidden" name="brand_name" value="{{$no_brand->brand}}">
                                            <button style="padding: 0 5px;font-size: 13px;" class="btn btn-primary small">
                                                создать бренд
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="{{route('admin.no_brands.delete_product')}}" method="post">
                                            @csrf
                                            <input type="hidden" name="brand_name" value="{{$no_brand->brand}}">
                                            <button style="padding: 0 5px;font-size: 13px;" class="btn btn-danger small">
                                                удалить
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <tr class="spacer"></tr>
                            @empty
                                <tr class="tr-shadow">
                                    <td colspan="4">
                                        <div class="alert alert-warning" role="alert">
                                            {{__('Страниц ещё нету')}}
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        @endisset

                        </tbody>
                    </table>
                </div>
                <!-- END DATA TABLE -->
            </div>
            <div class="col-sm-12">
                {{$no_brands->links()}}
            </div>
        </div>
        @component('admin.component.footer')@endcomponent
    </div>

@endsection
