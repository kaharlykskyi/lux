@extends('admin.layouts.admin')

@section('content')
    <div class="container-fluid m-t-75">

        <div class="row">
            <div class="col-12 m-t-15">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">{{__('Редактирование категории')}}</strong>
                    </div>
                    <div class="card-body">
                        <div class="card-text">
                            <form action="{{route('admin.category.update',$tecdoc_category->id)}}" method="post" enctype="multipart/form-data" class="form-horizontal">
                                @method('PUT')
                                @csrf
                                <input type="hidden" name="type" value="{{$type}}">
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="text-input" class=" form-control-label">{{__('Название')}}</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="text-input" name="name" value="{{isset($category->name)?$category->name:$tecdoc_category->description}}" class="form-control">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="file-input" class=" form-control-label">{{__('Картинка категории')}}</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input type="file" id="file-input" name="logo" class="form-control-file">
                                        <img style="max-width: 100px;" class="m-t-15" src="@if(isset($category)) {{asset('images/catalog/' . $category->logo)}} @else {{asset('images/map-locator.png')}} @endif" alt="">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-12">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fa fa-dot-circle-o"></i> {{__('Сохранить')}}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @component('admin.component.footer')@endcomponent
    </div>

@endsection