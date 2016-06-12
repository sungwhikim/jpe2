<div class="row col-lg-5 col-md-5 col-sm-5 col-xs-5 form-group"
     ng-class="{ 'has-error': (txForm.customer_id.$touched || txForm.$submitted) && txForm.customer_id.$invalid }">
    <label class="col-lg-3 control-label">Customer <span class="required-field glyphicon glyphicon-asterisk" /></label>
    <div class="col-lg-12 no-float">
        <select class="form-control" name="customer_id" ng-model="txCtrl.txData.customer_id"
                ng-options="customer.id as customer.name for customer in txCtrl.customers" required>
            <option value="">-- select a customer --</option>
        </select>
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="txForm.customer_id.$error" ng-if="txForm.$submitted || txForm.customer_id.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>


