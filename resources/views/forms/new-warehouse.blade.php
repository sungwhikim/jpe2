<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
     ng-class="{ 'has-error': (formNew.warehouse_id.$touched || formNew.$submitted) && formNew.warehouse_id.$invalid }">
    <label class="col-lg-3 control-label text-right">Warehouse <span class="required-field glyphicon glyphicon-asterisk" /></label>
    <div class="col-lg-8">
        <select class="form-control" name="warehouse_id" ng-model="mainList.newItem.warehouse_id"
                ng-options="warehouse.id as warehouse.name for warehouse in mainList.warehouses" required>
            <option value="">-- select a warehouse --</option>
        </select>
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="formNew.warehouse_id.$error" ng-if="formNew.$submitted || formNew.warehouse_id.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>


