<div class="row col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group" style="padding-right: 0;">
    <label class="control-label">Tracking Number</label>
    <div class="no-float">
        <input type="text" class="form-control" name="tracking_number" placeholder="Tracking Number"
               ng-model="txCtrl.txData.tracking_number" ng-maxlength="100"
               ng-model-options="{ updateOn: 'blur' }">
    </div>
</div>