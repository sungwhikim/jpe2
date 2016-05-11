<div class="row col-lg-5 col-md-5 col-sm-5 col-xs-5 form-group">
    <label class="col-lg-3 control-label">Bar Code</label>
    <div class="col-lg-12 no-float">
        <input type="text" class="form-control" name="barcode" placeholder="Tracking Number"
               ng-model="txCtrl.txData.newItem.barcode" ng-maxlength="100" ng-change="txCtrl.checkBarCode()"
               ng-model-options="{ updateOn: 'blur' }">
    </div>
</div>