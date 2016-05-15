<div class="row col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group" ng-show="txCtrl.selectedWarehouseClient.show_barcode_client">
    <label class="col-lg-3 control-label">Client Barcode</label>
    <div class="col-lg-12 no-float">
        <input type="text" class="form-control" name="barcode_client" placeholder="Client Barcode"
               ng-model="txCtrl.newItem.barcode_client" maxlength="100" ng-blur="txCtrl.checkBarcodeClient()">
    </div>
</div>