@extends('admin.layouts.admin')

@section('content')

    <div class="main-content p-t-85">
        <div class="section__content">
            @if (session('status'))
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="alert alert-info" role="alert">
                                {{ session('status') }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <h3 class="title-5 m-b-35 m-t-15">{{__('Категории горизонтального меню')}}</h3>
                    </div>
                    <div class="col-12 m-b-15 m-t-15 text-right">
                        <a href="{{route('admin.car_categories.create')}}" class="btn btn-primary">{{__('Создать')}}</a>
                    </div>
                    <div class="col-12">
                        <div class="table--no-card m-b-30">
                            <table class="table table-borderless table-striped table-earning">
                                <thead>
                                <tr>
                                    <th>Название</th>
                                    <th>Картинка</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @isset($car_category)
                                    @forelse($car_category as $item)
                                        <tr>
                                            <td>
                                                {{$item->title}}
                                            </td>
                                            <td>
                                                <img style="width: 40px;" src="{{asset('images/catalog/' . $item->logo)}}" alt="">
                                            </td>
                                            <td>
                                                <div class="table-data-feature">
                                                    <button onclick="location.href = '{{route('admin.car_categories.edit',['home_category' => $item->id])}}'" class="item" data-toggle="tooltip" data-placement="top" title="{{__('Редактирвать')}}">
                                                        <i class="zmdi zmdi-edit"></i>
                                                    </button>
                                                    <form onsubmit="if(confirm('DELETE?')){return true}else{return false}"
                                                          action="{{route('admin.car_categories.destroy',$item->id)}}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="item" data-toggle="tooltip" data-placement="top" title="{{__('Удалить')}}">
                                                            <i class="zmdi zmdi-delete"></i>
                                                        </button>
                                                    </form>
                                                    @if (isset($item->childCategories) && !empty($item->childCategories))
                                                        <button onclick="$('#child-categories_{{$item->id}}').toggle();" class="item" data-toggle="tooltip" data-placement="top" title="{{__('Дочерние категории')}}">
                                                            <i class="fa fa-sitemap" style="font-size: 17px;" aria-hidden="true"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @if (isset($item->childCategories) && !empty($item->childCategories))
                                            <tr style="display: none;margin: 10px 20px" id="child-categories_{{$item->id}}">
                                                <td colspan="5">
                                                    <ul class="list-group">
                                                        @foreach($item->childCategories as $category)
                                                            <li style="padding: 5px 10px;" class="list-group-item">
                                                                {{$category->title}}
                                                                <div style="position: absolute;top: 3px;right: 16px;" class="table-data-feature">
                                                                    <button onclick="location.href = '{{route('admin.car_categories.edit',['home_category' => $category->id])}}'" class="item" data-toggle="tooltip" data-placement="top" title="{{__('Редактирвать')}}">
                                                                        <i class="zmdi zmdi-edit"></i>
                                                                    </button>
                                                                    <form onsubmit="if(confirm('DELETE?')){return true}else{return false}"
                                                                          action="{{route('admin.car_categories.destroy',$category->id)}}" method="post">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="item" data-toggle="tooltip" data-placement="top" title="{{__('Удалить')}}">
                                                                            <i class="zmdi zmdi-delete"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                            </tr>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="5">
                                                <div class="alert alert-warning" role="alert">
                                                    <p>Категорий ещё нету</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                @endisset
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        {{$car_category->links()}}
                    </div>
                </div>
            </div>
        </div>
        @component('admin.component.footer')@endcomponent
    </div>
@endsection
