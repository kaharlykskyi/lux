<div class="card">
    <div class="card-body card-block">
        <form action="{{$link}}" method="get" id="filter_product" style="font-size: 13px;">
            <div class="row form-group">
                <div class="col col-sm-4">
                    <div class="row form-group">
                        <div class="col col-md-3">
                            <label for="supplier" class=" form-control-label">Производитель</label>
                        </div>
                        <div class="col-12 col-md-9">
                            <input type="text" id="supplier" value="{{request()->query('supplier')}}" name="supplier" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col col-sm-4">
                    <div class="row form-group">
                        <div class="col col-md-3">
                            <label for="article"  class=" form-control-label">Код</label>
                        </div>
                        <div class="col-12 col-md-9">
                            <input type="text" value="{{request()->query('article')}}" id="article" name="article" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col col-sm-4">
                    <div class="row form-group">
                        <div class="col col-md-3">
                            <label for="name" class=" form-control-label">Название</label>
                        </div>
                        <div class="col-12 col-md-9">
                            <input type="text" value="{{request()->query('name')}}" id="name" name="name" class="form-control">
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
                                    <option @if(request()->query('provider') == $provider->id) selected @endif value="{{$provider->id}}">{{$provider->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col col-sm-4">
                    <div class="row form-group">
                        <div class="col col-md-3">
                            <label for="prov_min_price" class=" form-control-label">Цена закупки</label>
                        </div>
                        <div class="col-12 col-md-9">
                            <input placeholder="min" style="width: 49%;float: left;" type="text" value="{{request()->query('prov_min_price')}}" id="prov_min_price" name="prov_min_price" class="form-control">
                            <input placeholder="max" style="width: 49%;" type="text" value="{{request()->query('prov_max_price')}}" id="prov_max_price" name="prov_max_price" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col col-sm-4">
                    <div class="row form-group">
                        <div class="col col-md-3">
                            <label for="min_price" class=" form-control-label">Цена с наценкой</label>
                        </div>
                        <div class="col-12 col-md-9">
                            <input placeholder="min" style="width: 49%;float: left;" type="text" value="{{request()->query('min_price')}}" id="min_price" name="min_price" class="form-control">
                            <input placeholder="max" style="width: 49%;" type="text" value="{{request()->query('max_price')}}" id="max_price" name="max_price" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col col-sm-4">
                    <div class="row form-group">
                        <div class="col col-md-3">
                            <label for="count" class=" form-control-label">Количество</label>
                        </div>
                        <div class="col-12 col-md-9">
                            <input type="text" value="{{request()->query('count')}}" id="count" name="count" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="card-footer">
        <button onclick="$('#filter_product').submit();" class="btn btn-primary btn-sm">
            <i class="fa fa-dot-circle-o"></i> Фильтровать
        </button>
        <button onclick="location.href = '{{$link}}'" class="btn btn-danger btn-sm">
            <i class="fa fa-ban"></i> Отменить
        </button>
    </div>
</div>
