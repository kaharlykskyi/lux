<!-- modal medium -->
<div class="modal fade" id="setBalance" tabindex="-1" role="dialog" aria-labelledby="setBalanceLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">{{__('Изменение баланса - ' . $user->name)}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.user.change_balance')}}" method="post" id="balanceForm" class="form-horizontal">
                    @csrf
                    <input type="hidden" name="user_id" value="{{$user->id}}">
                    <div class="row form-group">
                        <div class="col col-md-3">
                            <label class=" form-control-label">{{__('Имя')}}</label>
                        </div>
                        <div class="col-12 col-md-9">
                            <p class="form-control-static">{{$user->name}}</p>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col col-md-3">
                            <label for="textarea-input" class=" form-control-label">{{__('Описание')}}</label>
                        </div>
                        <div class="col-12 col-md-9">
                            <textarea name="description" id="textarea-input" rows="6" placeholder="Content..." class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col col-md-3">
                            <label for="select" class=" form-control-label">{{__('Валюта')}}</label>
                        </div>
                        <div class="col-12 col-md-9">
                            <select name="currency" id="select" class="form-control">
                                <option value="UAH">UAH</option>
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col col-md-3">
                            <label for="type_operation" class=" form-control-label">{{__('Тип операции')}}</label>
                        </div>
                        <div class="col-12 col-md-9">
                            <select name="type_operation" id="type_operation" class="form-control">
                                <option selected value="1">{{__('custom.type_operation_balance.1')}}</option>
                                <option value="2">{{__('custom.type_operation_balance.2')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col col-md-3">
                            <label for="change" class=" form-control-label">{{__('Сумма')}}</label>
                        </div>
                        <div class="col-12 col-md-9">
                            <input type="text" id="change" name="change" placeholder="100" class="form-control" required>
                            <small class="form-text text-muted">{{__('для списание введите отрицательное значение')}}</small>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Отмена')}}</button>
                <button onclick="$('#balanceForm').submit();" type="button" class="btn btn-primary">{{__('Пополнить')}}</button>
            </div>
        </div>
    </div>
</div>
<!-- end modal medium -->
