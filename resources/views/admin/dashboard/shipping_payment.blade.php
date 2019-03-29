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
            <div class="col-12 m-b-15">
                <a href="{{route('admin.dashboard')}}" class="btn btn-success">{{__('Назад')}}</a>
            </div>
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <strong>{{__('Редактирование ')}}</strong> {{__('информации про "Доставка и оплата"')}}
                    </div>
                    <div class="card-body card-block">
                        <form action="{{route('admin.shipping_payment')}}" method="post" class="form-horizontal">
                            @csrf

                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="content" class=" form-control-label">{{__('Доставка и оплата')}}</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <textarea id="content" name="content_file" rows="9" class="form-control">{!! $info !!}</textarea>
                                </div>
                            </div>

                            <script>
                                CKEDITOR.replace('content');
                            </script>

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
