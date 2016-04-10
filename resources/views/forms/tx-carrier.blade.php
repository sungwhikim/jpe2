<div class="row col-lg-5 col-md-5 col-sm-5 col-xs-5 form-group">
    <label class="col-lg-3 control-label">Carrier</label>
    <div class="col-lg-12 no-float">
        <select class="form-control" name="carrier_id" ng-model="txItem.carrier_id"
                ng-options="carrier.id as carrier.name for carrier in mainList.carriers">
            <option value="">-- select a carrier --</option>
        </select>
    </div>
</div>


