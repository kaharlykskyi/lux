@extends('admin.layouts.admin')

@section('content')
    <div class="container-fluid m-t-75">

        <div class="row">
            <div class="col-12 m-b-15 m-t-15">
                <a href="{{route('admin.all_category.index')}}" class="btn btn-success">{{__('Назад')}}</a>
            </div>
            <div class="col-12 m-t-15">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">{{__('Редактирование категории')}}</strong>
                    </div>
                    <div class="card-body">
                        <div class="card-text">
                            <form action="{{route('admin.all_category.edit')}}" method="post" enctype="multipart/form-data" class="form-horizontal">
                                @csrf

                                <input type="hidden" name="level" value="{{request()->query('level')}}">
                                <input type="hidden" name="parent" value="{{request()->query('parent')}}">
                                <input type="hidden" name="tecdoc_id" value="{{request()->query('id')}}">

                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="tecdoc_name" class=" form-control-label">{{__('Название в TecDoc')}}</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="tecdoc_name" name="tecdoc_name" value="{{$search_category}}" readonly>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="text-input" class=" form-control-label">{{__('Название')}}</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="text-input" name="name" value="{{isset($save_category->name)?$save_category->name:$search_category}}" class="form-control">
                                        @if ($errors->has('name'))
                                            <small class="form-text text-danger">{{ $errors->first('name') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="hurl" class=" form-control-label">{{__('Синоним для дружественого урла')}}</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        @if(!isset($save_category))
                                            <input type="text" id="hurl" name="hurl" value="{{old('hurl')}}" class="form-control">
                                            @if ($errors->has('hurl'))
                                                <small class="form-text text-danger">{{ $errors->first('hurl') }}</small>
                                            @endif
                                            <small class="form-text text-info">Должно быть уникальным.Если не заполнить то сгенерируеться автоматически</small>
                                        @else
                                            <p class="h5">{{$save_category->hurl}}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="show" class=" form-control-label">{{__('Отображать')}}</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input style="display: block;width: 20px;height: 20px;" type="checkbox" id="show" @if(isset($save_category) && $save_category->show === 1) checked @endif name="show" value="1" class="form-control">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="file-input" class=" form-control-label">{{__('Картинка категории')}}</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input type="file" id="file-input" name="logo" class="form-control-file">
                                        <img style="max-width: 100px;" class="m-t-15" src="@if(isset($save_category)) {{asset('images/catalog/' . $save_category->image)}} @else {{asset('images/map-locator.png')}} @endif" alt="">
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
