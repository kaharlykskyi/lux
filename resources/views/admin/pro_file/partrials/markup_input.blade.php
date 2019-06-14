<div class="form-row">
    <div class="form-group col-md-4">
        <label>min<input type="text" value="{{isset($item)?$item->min:''}}" class="form-control"></label>
    </div>
    <div class="form-group col-md-4">
        <label>max(включительно)<input type="text" value="{{isset($item)?$item->max:''}}" class="form-control"></label>
    </div>
    <div class="form-group col-md-4">
        <label>наценка<input type="text" value="{{isset($item)?$item->markup:''}}" class="form-control"></label>
    </div>
</div>
