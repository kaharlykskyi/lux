@extends('admin.layouts.admin')

@section('content')

    <div class="container-fluid m-t-75">
        @if (session('status'))
            <div class="row p-t-10">
                <div class="col-sm-12">
                    <div class="alert alert-info" role="alert">
                        {{ session('status') }}
                    </div>
                </div>
            </div>
        @endif
        <div class="row p-t-10">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <strong>{{__('Редактирование ')}}</strong> {{__('рекламного банера')}}
                    </div>
                    <div class="card-body card-block">
                        <form action="{{route('admin.advertising')}}" method="post" class="form-horizontal">
                            @csrf

                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="content" class=" form-control-label">{{__('Код рекламы')}}</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <textarea id="content" name="content_file" rows="9" class="form-control">{!! $info !!}</textarea>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fa fa-dot-circle-o"></i> {{__('Сохранить')}}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @component('admin.component.footer')@endcomponent
    </div>

@endsection
