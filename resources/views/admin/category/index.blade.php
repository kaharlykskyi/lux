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
            <div class="col-12">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('admin.category.index')}}">{{__('Легковые авто')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('admin.category.index','comercial')}}">{{__('Грузовые авто')}}</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-12">
                <!-- DATA TABLE -->
                <h3 class="title-5 m-b-35 m-t-15">{{__('Категории сайта')}}</h3>
                <div class="table-responsive table-responsive-data2">
                    <table class="table table-data2">
                        <thead>
                        <tr>
                            <th>{{__('Картинка')}}</th>
                            <th>{{__('Название')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @isset($categories)
                            @forelse($categories as $category)
                                <tr>
                                    <th>
                                        <img style="width: 35px;height: auto;" src="@if(isset($category->image_data)) {{asset('images/catalog/' . $category->image_data->logo)}} @else {{asset('images/map-locator.png')}} @endif" alt="">
                                    </th>
                                    <th>{{$category->description}}</th>
                                    <th>
                                        <div class="table-data-feature">
                                            <button onclick="location.href = '{{route('admin.category.edit',$category->id)}}?type={{$type}}'" class="item" data-toggle="tooltip" data-placement="top" title="{{__('Редактирвать')}}">
                                                <i class="zmdi zmdi-edit"></i>
                                            </button>
                                        </div>
                                    </th>
                                </tr>
                            @empty
                                <tr class="tr-shadow">
                                    <td colspan="4">
                                        <div class="alert alert-warning" role="alert">
                                            {{__('Данных ещё не обнаружено')}}
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
            @isset($categories)
                <div class="col-sm-12">
                    {{$categories->links()}}
                </div>
            @endisset
        </div>
        @component('admin.component.footer')@endcomponent
    </div>

@endsection