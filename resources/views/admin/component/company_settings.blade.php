<div class="modal fade" id="companySettingsModal" tabindex="-1" role="dialog" aria-labelledby="companySettingsModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="companySettingsModalLabel">Обновление Данных ФОП</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.company.settings')}}" method="post" id="company_settings">
                    @csrf
                    @php
                        $settings = App\StoreSettings::where('type','company')->first();
                        if ($settings){
                            $data = json_decode($settings->settings);
                        }
                    @endphp
                    <input type="hidden" name="type" value="company">
                    <div class="form-group">
                        <label for="company_name">Название компании</label>
                        <input type="text" class="form-control" value="@isset($data) {{$data->company_name}} @endisset" id="company_name" name="company_name">
                    </div>
                    <div class="form-group">
                        <label for="company_address">Адресс компании</label>
                        <input type="text" class="form-control" value="@isset($data) {{$data->company_address}} @endisset" id="company_address" name="company_address">
                    </div>
                    <div class="form-group">
                        <label for="company_tel">Телефон компании</label>
                        <input type="text" class="form-control" value="@isset($data) {{$data->company_tel}} @endisset" id="company_tel" name="company_tel">
                    </div>
                    <div class="form-group">
                        <label for="company_bank">Банк</label>
                        <input type="text" class="form-control" value="@isset($data) {{$data->company_bank}} @endisset" id="company_bank" name="company_bank">
                    </div>
                    <div class="form-group">
                        <label for="company_code">Код компании</label>
                        <input type="text" class="form-control" value="@isset($data) {{$data->company_code}} @endisset" id="company_code" name="company_code">
                    </div>
                    <div class="form-group">
                        <label for="company_mfo">МФО компании</label>
                        <input type="text" class="form-control" value="@isset($data) {{$data->company_mfo}} @endisset" id="company_mfo" name="company_mfo">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрить</button>
                <button type="button" onclick="updateCompanySettings()" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </div>
</div>

<script>
    function updateCompanySettings() {
        if (confirm('Сохранить данные?')){
            $.post(`${$('#company_settings').attr('action')}`,$('#company_settings').serialize(),function (data) {
                if (data){
                    alert('Данные обновлены');
                }
            });
        }
    }
</script>
