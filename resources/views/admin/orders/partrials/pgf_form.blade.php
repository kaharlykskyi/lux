<!-- Modal -->
<div class="modal fade" id="oderInfoModal" tabindex="-1" role="dialog" aria-labelledby="oderInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 99%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="oderInfoModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.order.pdf')}}" method="post" id="pdf_data_form">
                    @csrf
                    <div class="form-group">
                        <label for="company_name">Название компании</label>
                        <input type="text" class="form-control" value="{{config('company_info.name')}}" id="company_name" name="company_name">
                    </div>
                    <div class="form-group">
                        <label for="company_address">Адресс компании</label>
                        <input type="text" class="form-control" value="{{config('company_info.address')}}" id="company_address" name="company_address">
                    </div>
                    <div class="form-group">
                        <label for="company_tel">Телефон компании</label>
                        <input type="text" class="form-control" value="{{config('company_info.tel')}}" id="company_tel" name="company_tel">
                    </div>
                    <div class="form-group">
                        <label for="company_bank">Банк</label>
                        <input type="text" class="form-control" value="{{config('company_info.bank')}}" id="company_bank" name="company_bank">
                    </div>
                    <div class="form-group">
                        <label for="company_code">Код компании</label>
                        <input type="text" class="form-control" value="{{config('company_info.code')}}" id="company_code" name="company_code">
                    </div>
                    <div class="form-group">
                        <label for="company_mfo">МФО компании</label>
                        <input type="text" class="form-control" value="{{config('company_info.mfo')}}" id="company_mfo" name="company_mfo">
                    </div>
                    <div id="product_oder_pdf" class="table-responsive"></div>
                    <div class="form-group">
                        <label for="client_info">Информация для покупателя</label>
                        <textarea class="form-control" name="client_info" id="client_info" rows="5">@if(session()->has('client_info')){!! session('client_info') !!}@endif</textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                <button onclick="if(confirm('Сформировать товарный чек?')){$('#pdf_data_form').submit()}else{return false}" type="button" class="btn btn-primary">Сформировать</button>
            </div>
        </div>
    </div>
</div>

<script>
    function getOderInfo(id) {
        $('#oderInfoModalLabel').text(`Данные по заказу #${id}`);
        $('#product_oder_pdf').html('<p class="text-center"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></p>');

        $.get(`{{route('admin.order.pdf')}}?id=${id}`,function (data) {
            let html = `<input type="hidden" name="id" value="${id}">
                            <table class="table">
                              <thead>
                                <tr>
                                  <th scope="col">#</th>
                                  <th scope="col">№ детали</th>
                                  <th scope="col">Название</th>
                                  <th scope="col">Кол.</th>
                                  <th scope="col">Цена</th>
                                  <th scope="col">Цена со скидкой</th>
                                  <th scope="col">Сумма</th>
                                </tr>
                              </thead>
                              <tbody style="font-size: 13px;">
                            `;

            let sum = 0;
            console.log(data);
            data.cart_product.forEach(function (item,key) {
                const price_dicount = data.client.discount != null?parseFloat(item.price) - (parseFloat(item.price) * data.client.discount.percent / 100):null;
                const product_sum = price_dicount !== null?price_dicount * item.pivot.count:parseFloat(item.price) * item.pivot.count;

                html += `
                            <tr>
                              <th scope="row">${key + 1}</th>
                              <td><input style="background: #cccccc5e;padding: 5px;" type="text" name="product_article[]" value="${item.articles}"></td>
                              <td><input style="background: #cccccc5e;padding: 5px;" type="text" name="product_name[]" value="${item.name}"></td>
                              <td><input style="background: #cccccc5e;padding: 5px;" type="text" name="product_col[]" value="${item.pivot.count}"></td>
                              <td><input style="background: #cccccc5e;padding: 5px;" type="text" name="product_price[]" value="${item.price}"></td>
                              <td><input style="background: #cccccc5e;padding: 5px;" type="text" name="product_price_discount[]" value="${price_dicount !== null?Number((price_dicount).toFixed(2)):item.price}"></td>
                              <td><input style="background: #cccccc5e;padding: 5px;" type="text" name="product_sum[]" value="${Number((product_sum).toFixed(2))}"></td>
                            </tr>
                    `;

                sum += product_sum;
            });

            html += `<tr>
                            <th class="text-right" colspan="10" scope="row">
                                <span>Всего: </span> <input style="background: #cccccc5e;padding: 5px;" type="text" name="sum" value="${Number((sum).toFixed(2))}">
                            </th>
                        </tr></tbody></table>
                    <div class="form-group">
                        <label for="price_abc">Всего к оплате буквами</label>
                        <input type="text" class="form-control" id="price_abc" name="price_abc">
                    </div>
                   <hr style="margin: 10px 0;">
                   <div class="form-group">
                        <label for="name_user">ФИО заказчика</label>
                        <input type="text" value="${data.client.sername + ' ' + data.client.name + ' ' + data.client.last_name}" class="form-control" id="name_user" name="name_user">
                   </div>
                   <div class="form-group">
                        <label for="client_id">Код заказчика</label>
                        <input type="text" value="${data.client.id}" class="form-control" id="client_id" name="client_id">
                   </div>
                    <div class="form-group">
                        <label for="client_phone">Телефон заказчика</label>
                        <input type="text" value="${data.client.phone}" class="form-control" id="client_phone" name="client_phone">
                    </div>
                     `;



            $('#product_oder_pdf').html(html);
        });
    }

    CKEDITOR.replace( 'client_info' );
</script>
