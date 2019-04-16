<div class="card">
    <div class="card-body card-block">
        <form action="{{$link}}" method="get" id="filter_product">
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
