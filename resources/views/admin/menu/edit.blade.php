@extends('admin.layouts.admin')

@section('content')
    <div class="container-fluid m-t-75">

        <div class="row">
            <div class="col-12 m-t-15">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">{{__('Редактирование категории')}}</strong>
                        @if(session('status'))
                            <small>
                                <span class="badge badge-success float-right mt-1">{{session('status')}}</span>
                            </small>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="card-text">
                            <form action="{{route('admin.menu.edit')}}" method="post" class="form-horizontal">
                                @csrf
                                <input type="hidden" name="tecdoc_title" value="{{$tecdoc_name}}">
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="text-input" class=" form-control-label">{{__('Название')}}</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="text-input" name="title" value="{{isset($save_category->title)?$save_category->title:$tecdoc_name}}" class="form-control">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="show_menu" class=" form-control-label">{{__('Показывать в меню')}}</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input style="width: 20px;height: 20px;" type="checkbox" @if(isset($save_category) && $save_category->show_menu === 1) checked @endif id="show_menu" value="1"  name="show_menu" class="form-control-file">
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
