<div class="report-control" style="width: 200px; margin-right: 0px;"
     ng-class="{ 'has-error': (reportForm.tx_type.$touched || reportForm.$submitted) && reportForm.tx_type.$invalid }">
    <label class="control-label">Transaction Type <span class="required-field glyphicon glyphicon-asterisk" /></label>
    <select class="form-control" name="tx_type" ng-model="ctrl.criteria.tx_type" required>
        <option value="">-- select transaction --</option>
        <option value="asn">ASN - Advanced Shipping Notice</option>
        <option value="csr">CSR - Client Stock Release</option>
        <option value="receive">Receiving</option>
        <option value="ship">Shipping</option>
    </select>
    <!-- ngMessages goes here -->
    <div class="help-block ng-messages" ng-messages="reportForm.tx_type.$error"
         ng-if="reportForm.$submitted || reportForm.tx_type.$touched" ng-cloak>
        @include('layouts.validate-message')
    </div>
</div>