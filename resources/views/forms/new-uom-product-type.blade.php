<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group">
    <label class="col-lg-3 control-label text-right">{{ $number }}</label>
    <div class="col-lg-8 text-toggle-combo">
        <div class="col-lg-7 col-md-6 col-sm-6 col-xs-6">
            <input type="text" class="form-control" name="uom{{ $number }}" placeholder="UOM {{ $number }}"
                   ng-model="mainList.newItem.uom{{ $number }}" ng-maxlength="30"
                   ng-model-options="{ updateOn: 'blur' }">
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
            <div class="checkbox checkbox-slider--b checkbox-slider-md">
                <label>
                    <input type="checkbox" name="uom{{ $number }}_active" value="true" ng-model="mainList.newItem.uom{{ $number }}_active"><span>Active</span>
                </label>
            </div>
        </div>
    </div>
</div>