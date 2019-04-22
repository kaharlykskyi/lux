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
                <h3 class="title-5 m-b-35 m-t-15">{{__('Общие категории сайта')}}</h3>
                <span class="small text-info">
                    Перед редактированием дочерних категори, сначала сохраните родительскую, что бы была коректная связь
                </span>
                <div class="table-responsive table-responsive-data2">
                    <table class="table table-data2">
                        <thead>
                        <tr>
                            <th>{{__('Картинка')}}</th>
                            <th>{{__('Название')}}</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @isset($categories)
                            @forelse($categories as $category)
                                @if(!empty($category->name))
                                    <tr>
                                        <th>
                                            @php $img = DB::table('all_category_trees')->where('tecdoc_name',$category->name)->where('level',request()->has('level')?(int)request('level'):0)->first(); @endphp
                                            <img style="width: 35px;height: auto;" src="@if(isset($img->image)) {{asset('images/catalog/' . $img->image)}} @else {{asset('images/map-locator.png')}} @endif" alt="">
                                        </th>
                                        <th>{{$category->name}}</th>
                                        <th>
                                            <div class="table-data-feature">
                                                <button onclick="location.href = '{{route('admin.all_category.edit')}}?category={{$category->name}}&level={{request()->query('level')}}&parent={{$parent}}'" class="item" data-toggle="tooltip" data-placement="top" title="{{__('Редактирвать')}}">
                                                    <i class="zmdi zmdi-edit"></i>
                                                </button>
                                                @if((int)request()->query('level') < 3)
                                                    <button onclick="location.href = '{{route('admin.all_category.index')}}?parent_category={{$category->name}}&level={{request()->has('level')?(int)request()->query('level') + 1:1}}'" class="item" data-toggle="tooltip" data-placement="top" title="{{__('Дочерние категории')}}">
                                                        <i class="fa fa-sitemap" style="font-size: 17px;" aria-hidden="true"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </th>
                                    </tr>
                                @endif
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
