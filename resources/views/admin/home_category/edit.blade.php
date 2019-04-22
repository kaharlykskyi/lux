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
        <div class="row">
            <div class="col-12 m-b-15 m-t-15">
                <a href="{{route('admin.home_category.index')}}" class="btn btn-success">{{__('Назад')}}</a>
            </div>
        </div>
        <div class="row p-t-10">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <strong>{{__('Редактирование ')}}</strong> {{__('категории ' . $homeCategoryGroup->name)}}
                    </div>
                    <div class="card-body card-block">
                        <form action="{{route('admin.home_category.update',$homeCategoryGroup->id)}}" method="post" class="form-horizontal">
                            @method('PUT')
                            @csrf

                            @include('admin.home_category.partrials.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @component('admin.component.footer')@endcomponent
    </div>

@endsection
