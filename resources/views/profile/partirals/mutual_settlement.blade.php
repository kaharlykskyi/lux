<div class="panel panel-primary">
    <div class="panel-heading">{{__('Взаиморасчеты')}}</div>
    <div class="panel-body panel-profile">
        <div class="row login-sec">
            <div class="col-sm-12" style="overflow: visible;">
                <table class="table table-bordered table-hover" id="creaking_account">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Дата</th>
                        <th>Приход</th>
                    </tr>
                    </thead>
                    <tbody>
                    @isset($mutual_settelement)
                        @forelse($mutual_settelement as $item)
                            <tr>
                                <td class="identification-wrapper">
                                    <i class="fa fa-plus-square-o" style="cursor: pointer" aria-hidden="true"></i>
                                    <div class="identification-info">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>Вид действия</th>
                                                    <th>Описание</th>
                                                    <th>Изменения</th>
                                                    <th>Остаток</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>{{__('custom.type_operation_balance.' . $item->type_operation)}}</td>
                                                    <td>{{$item->description}}</td>
                                                    <td>{{(int)$item->change}}</td>
                                                    <td>{{(int)$item->balance}}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </td>
                                <td>{{date_format($item->created_at, 'Y-m-d')}}</td>
                                <td>{{(int)$item->change}}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="alert alert-info margin-15" role="alert">
                                        {{__('Платежи ещё не производились')}}
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    @endisset
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
