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
                        <strong>{{__('Редактирование ')}}</strong> {{__('профайла ' . $proFile->name)}}
                    </div>
                    <div class="card-body card-block">
                        <form action="{{route('admin.pro_file.update',$proFile->id)}}" method="post" class="form-horizontal" id="pro_file_form">
                            @method('PUT')
                            @csrf

                            @include('admin.pro_file.partrials.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @component('admin.component.footer')@endcomponent
    </div>

@endsection
