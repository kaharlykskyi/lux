<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 98%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Добавление товара к заказу</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body card-block">
                                <form action="{{route('admin.order.search_product')}}" method="get" id="filter_product" style="font-size: 13px;">
                                    <div class="row form-group">
                                        <div class="col col-sm-4">
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="article"  class=" form-control-label">Код</label>
                                                </div>
                                                <div class="col-12 col-md-9">
                                                    <input type="text" id="article" name="article" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col col-sm-4">
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="name" class=" form-control-label">Название</label>
                                                </div>
                                                <div class="col-12 col-md-9">
                                                    <input type="text" id="name" name="name" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col col-sm-4">
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="provider" class=" form-control-label">Поставщик</label>
                                                </div>
                                                <div class="col-12 col-md-9">
                                                    <select name="provider" id="provider" class="form-control">
                                                        <option value="0"></option>
                                                        @foreach($providers as $provider)
                                                            <option value="{{$provider->id}}">{{$provider->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col col-sm-4">
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="count" class=" form-control-label">Количество</label>
                                                </div>
                                                <div class="col-12 col-md-9">
                                                    <input type="text" id="count" name="count" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer">
                                <button onclick="searchProduct()" class="btn btn-primary btn-sm">
                                    <i class="fa fa-dot-circle-o"></i> Искать
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive table--no-card m-b-30">
                            <table class="table table-borderless table-striped table-earning">
                                <thead>
                                <tr>
                                    <th>Артикул</th>
                                    <th>Наименование</th>
                                    <th>Бренд</th>
                                    <th>Цена</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="response"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function searchProduct(){
        const data = $('#filter_product').serialize();
        $('#response').html('<tr><td colspan="6"><p class="text-center"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i>\n</p></td></tr>');
        $.get(`${$('#filter_product').attr('action')}?${data}`,function (response) {
            let html = '';
            response.forEach(function (item) {
                html += `
                    <tr>
                        <td>${item.articles}</td>
                        <td>${item.name}</td>
                        <td>${item.matchcode}</td>
                        <td>${item.price}</td>
                        <td>
                            <input style="border: 1px solid;width: 50px;text-align: center;" type="number" value="1" id="prod_${item.id}">
                            <button onclick="addProduct('${item.id}')" style="padding: 0 5px;font-size: 13px;" class="btn btn-success small">
                                добавить
                            </button>
                        </td>
                    </tr>
                `;
            });

            $('#response').html(html);
        });
    }

    function addProduct(id) {
        const count = $(`#prod_${id}`).val();
        $.post('{{route('admin.order_edit',request('order'))}}',{_token:'{{csrf_token()}}',id:id,count:count},function (data) {
            $('#order-product-block').html(htmlProduct(data.products));
            alert(data.mass);
        });
    }
</script>
