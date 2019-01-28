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

                        <h2>Привет, {{Auth::user()->name}}</h2>

                        <p>Пожалуйста, нажмите кнопку ниже, чтобы подтвердить свой адрес электронной почты.</p>

                        <table>
                            <tr>
                                <td align="center">
                                    <p>
                                        <a href="{{$link}}" class="button">Подтвердите Адрес Электронной Почты</a>
                                    </p>
                                </td>
                            </tr>
                        </table>

                        <p>Если вы не создали учетную запись, никаких дальнейших действий не требуется.</p>

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