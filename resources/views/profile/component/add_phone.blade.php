<div class="modal fade" tabindex="-1" role="dialog" id="add_user_phone_modal" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{__('Добавление номера телефона')}}</h4>
            </div>
            <div class="modal-body">
                <ul class="row login-sec">
                    <li class="col-sm-12">
                        <label>{{__('Номер телефона')}}
                            <input type="tel" id="dop_user_phone_input" class="form-control phone_mask" name="phone" placeholder="380452712312" aria-describedby="basic-addon1" required>
                        </label>
                    </li>
                    <li class="col-sm-12 text-left">
                        <button style="background-color: #337ab7;" type="button" class="btn btn-default" data-dismiss="modal">{{__('Закрыть')}}</button>
                        <button onclick="addPhone()" style="background-color: #337ab7;" type="button" class="btn btn-primary">{{__('Добавить номер')}}</button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<script>
    function addPhone() {
        $.post('{{route('dop_user_phone')}}',{phone:$('#dop_user_phone_input').val(),_token:'{{csrf_token()}}'},function (data) {
            if (data.errors !== undefined){
                alert(data.errors);
            } else if (data.response !== undefined){
                $('#list_user_phone').append(`<li id="phone_${data.response.id}" class="list-group-item">
                                    <span class="badge"><i class="fa fa-trash-o" aria-hidden="true"></i></span>
                                    ${data.response.phone}
                                    </li>`);
                $('#add_user_phone_modal').modal('hide');
                $('#dop_user_phone_input').val('');
            }
        });
    }
</script>
