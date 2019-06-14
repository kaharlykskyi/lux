<div class="form-row" style="position: relative;" @isset($k) id="markup_row_{{$k}}" @endisset>
    @isset($k)
        <span style="cursor: pointer;position: absolute;top: 0;right: 15px;z-index: 1000;" onclick="deleteMarkupRow('{{$k}}')">
            <i class="fa fa-minus" aria-hidden="true"></i>
        </span>
    @endisset
    <div class="form-group col-md-4">
        <label>min<input type="text" value="{{isset($data)?$data->min:''}}" class="form-control"></label>
    </div>
    <div class="form-group col-md-4">
        <label>max(включительно)<input type="text" value="{{isset($data)?$data->max:''}}" class="form-control"></label>
    </div>
    <div class="form-group col-md-4">
        <label>наценка<input type="text" value="{{isset($data)?$data->markup:''}}" class="form-control"></label>
    </div>
</div>
