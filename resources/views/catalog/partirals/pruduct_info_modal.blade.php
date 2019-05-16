<div class="modal fade" id="productInfoModal" tabindex="-1" role="dialog" aria-labelledby="productInfoModalLabel">
    <div class="modal-dialog" role="document" style="max-width: 98%;width: 98%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h6 class="modal-title" id="productInfoModalLabel"></h6>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>

<script>
    function productInfo(article,supplier) {
        $('#productInfoModalLabel').text(`Детальная информация по товару - ${article}`);
        $('#productInfoModal .modal-body').html('<p class="text-center"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i></p>');

        $.post('{{route('product.full_info')}}',{_token:'{{csrf_token()}}',article,supplier},function (data) {
            let html = `
                <div class="row">
                  <div class="col-sm-6 col-md-3">
                    <div class="thumbnail">
                      <img src="${data.file !== null?data.file.PictureName:'{{asset('images/default-no-image_2.png')}}'}" alt="...">
                      <div class="caption">
                        <h5>${data.product.name}</h5>
                        <p>${data.product.short_description !== null?data.product.short_description:''}</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="nav nav-tabs" role="tablist">
                            ${data.attr !== null?'<li role="presentation" class="active"><a href="#attribute" aria-controls="home" role="tab" data-toggle="tab">Параметры</a></li>':''}
                            ${data.vehicles !== null?'<li role="presentation"><a href="#vehicles" aria-controls="profile" role="tab" data-toggle="tab">Применяемость</a></li>':''}
                        </ul>
                        <div class="tab-content">

            `;

            if (data.attr !== null){
                html += '<div role="tabpanel" class="tab-pane active" id="attribute"><table class="table"><tbody>';
                data.attr.forEach(function (item) {
                    html += `<tr>
                                <td>${item.description}</td>
                                <td>${item.displayvalue}</td>
                            </tr>`;
                });
                html += '</tbody></table></div>';
            }


            $('#productInfoModal .modal-body').html(html + '</div></div>');
            console.log(data)
        });
    }
</script>
