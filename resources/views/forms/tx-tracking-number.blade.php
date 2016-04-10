<div class="row col-lg-{{ $size }} col-md-{{ $size }} col-sm-{{ $size }} col-xs-{{ $size }} form-group">
    <label class="col-lg-3 control-label">Tracking Number</label>
    <div class="col-lg-12 no-float">
        <input type="text" class="form-control" name="tracking_number" placeholder="Tracking Number"
               ng-model="txItem.tracking_number" ng-maxlength="100"
               ng-model-options="{ updateOn: 'blur' }">
    </div>
</div>