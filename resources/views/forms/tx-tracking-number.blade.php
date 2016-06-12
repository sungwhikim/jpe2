<div class="row col-lg-8 col-md-8 col-sm-8 col-xs-8 form-group">
    <label class="col-lg-3 control-label">Tracking Number</label>
    <div class="col-lg-12 no-float">
        <input type="text" class="form-control" name="tracking_number" placeholder="Tracking Number"
               ng-model="txCtrl.txData.tracking_number" ng-maxlength="100"
               ng-model-options="{ updateOn: 'blur' }">
    </div>
</div>