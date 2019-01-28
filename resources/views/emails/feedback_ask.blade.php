@extends('emails.layout')

@section('content_email')

    <table class="body-wrap">
        <tr>
            <td class="container">

                <!-- Message start -->
                <table>
                    <tr>
                        <td align="center" class="masthead">
                            <h1>{{config('app.name')}}</h1>
                        </td>
                    </tr>
                    <tr>
                        <td class="content">

                            <p>{{$mass}}</p>

                            <p><em>– Администрация "{{config('app.name')}}"</em></p>

                        </td>
                    </tr>
                </table>

            </td>
        </tr>
        <tr>
            <td class="container">

                <!-- Message start -->
                <table>
                    <tr>
                        <td class="content footer" align="center">
                            <p>Отправлено от лица <a href="{{config('app.url')}}">{{config('app.name')}}</a>, {{config('app.company_location')}}</p>
                            <p><a href="mailto:{{config('app.work_mail')}}">{{config('app.work_mail')}}</a></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

@endsection