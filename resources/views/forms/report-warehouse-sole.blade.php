<div class="col-lg-2 col-md-2 col-sm-2 col-xs-3 report-control"
     ng-class="{ 'has-error': (reportForm.warehouse_id.$touched || reportForm.$submitted) && reportForm.warehouse_id.$invalid }">
    <label class="control-label text-nowrap">Warehouse <span class="required-field glyphicon glyphicon-asterisk" /></label>
    <select class="form-control" name="warehouse_id" ng-model="ctrl.criteria.warehouse_id"
            ng-options="warehouse.id as warehouse.name for warehouse in ctrl.wcList" required>
        <option value="">-- select --</option>
    </select>
    <!-- ngMessages goes here -->
    <div class="help-block ng-messages" ng-messages="reportForm.warehouse_id.$error"
         ng-if="reportForm.$submitted || reportForm.warehouse_id.$touched" ng-cloak>
        @include('layouts.validate-message')
    </div>
</div>