<div class="modal fade" id="sendFeedBack" tabindex="-1" role="dialog" aria-labelledby="sendFeedBack" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{__('Составление ответа на вопрос')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.feedback.ask')}}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="sender-name" class="col-form-label">Отправитель:</label>
                        <input type="text" class="form-control" id="sender-name" name="sender" value="{{config('mail.from.address')}}">
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Получатель:</label>
                        <input type="text" class="form-control" id="recipient-name" name="recipient">
                    </div>
                    <div class="form-group">
                        <label for="subject" class="col-form-label">Тема письма:</label>
                        <input type="text" class="form-control" id="subject" name="subject" value="Ответ на заданый вопрос">
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Сообщение:</label>
                        <textarea class="form-control" id="message-text" name="message"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Отмена')}}</button>
                <button type="button" onclick="$('#sendFeedBack form').submit();" class="btn btn-primary">{{__('Отправить')}}</button>
            </div>
        </div>
    </div>
</div>

<script>
    function showFeedBackModal(email) {
        $('#recipient-name').val(email);
        $('#sendFeedBack').modal('show');
    }
</script>