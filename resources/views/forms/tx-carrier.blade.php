<div class="row col-lg-{{ $size }} col-md-{{ $size }} col-sm-{{ $size }} col-xs-{{ $size }} form-group">
    <label class="col-lg-3 control-label">Carrier</label>
    <div class="col-lg-12 no-float">
        <select class="form-control" name="carrier_id" ng-model="txCtrl.txData.carrier_id"
                ng-options="carrier.id as carrier.name for carrier in txCtrl.carriers">
            <option value="">-- select a carrier --</option>
        </select>
    </div>
</div>


