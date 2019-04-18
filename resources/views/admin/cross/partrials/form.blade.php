<div class="row form-group">
    <div class="col col-md-3">
        <label for="manufacturerId" class=" form-control-label">{{__('Марка')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <select name="manufacturerId" id="manufacturerId" class="form-control">
            @foreach($brands as $brand)
                <option @if((int)request()->query('manufacturerId') === $brand->id) selected @endif value="{{$brand->id}}">{{$brand->matchcode}}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="name" class=" form-control-label">{{__('Артикль')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="PartsDataSupplierArticleNumber" name="PartsDataSupplierArticleNumber" value="{{request()->query('PartsDataSupplierArticleNumber')}}" class="form-control" required>
        @if ($errors->has('PartsDataSupplierArticleNumber'))
            <small class="form-text text-danger">{{ $errors->first('PartsDataSupplierArticleNumber')}}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="SupplierId" class=" form-control-label">{{__('Производитель кросс запчасти')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <select name="SupplierId" id="SupplierId" class="form-control">
            @foreach($providers as $provider)
                <option @if((int)request()->query('SupplierId') == $provider->id) selected @endif value="{{$provider->id}}">{{$provider->matchcode}}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="OENbr" class=" form-control-label">{{__('Код кросс запчасти')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="OENbr" name="OENbr" value="{{request()->query('OENbr')}}" required class="form-control">
        @if ($errors->has('OENbr'))
            <small class="form-text text-danger">{{ $errors->first('OENbr') }}</small>
        @endif
    </div>
</div>


<button type="submit" class="btn btn-primary btn-sm">
    <i class="fa fa-dot-circle-o"></i> {{__('Сохранить')}}
</button>
