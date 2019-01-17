<div class="modal fade" id="orderInfo" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderInfoTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive m-b-40">
                    <table class="table table-borderless table-data3 hidden" id="stock_product">
                        <thead>
                        <tr>
                            <th>Склад</th>
                            <th>Компания</th>
                            <th>Остатки</th>
                        </tr>
                        </thead>
                        <tbody id="">
                        </tbody>
                    </table>
                    <table class="table table-borderless table-data3">
                        <thead>
                        <tr>
                            <th>id Товара</th>
                            <th>Артикль</th>
                            <th>Название</th>
                            <th>Цена</th>
                            <th>Количество</th>
                        </tr>
                        </thead>
                        <tbody id="dataOrder">
                        <tr>
                            <td colspan="5">
                                <p class="text-center">
                                    <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                                    <span class="sr-only">Loading...</span>
                                </p>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Закрыть')}}</button>
            </div>
        </div>
    </div>
</div>