<div class="report-control"
     ng-class="{ 'has-error': (reportForm.client_id.$touched || reportForm.$submitted) && reportForm.client_id.$invalid }">
    <label class="control-label">Client <span class="required-field glyphicon glyphicon-asterisk" /></label>
    <select class="form-control" name="client_id" ng-model="ctrl.criteria.client_id" ng-change="ctrl.selectClient();"
            ng-options="client.id as client.short_name for client in (ctrl.wcList | filter:{id:ctrl.criteria.warehouse_id})[0].clients" required>
        <option value="">-- select --</option>
    </select>
    <!-- ngMessages goes here -->
    <div class="help-block ng-messages" ng-messages="reportForm.client_id.$error"
         ng-if="reportForm.$submitted || reportForm.client_id.$touched" ng-cloak>
        @include('layouts.validate-message')
    </div>
</div>