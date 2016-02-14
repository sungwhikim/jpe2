<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
     ng-class="{ 'has-error': (formNew.billing_currency_id.$touched || formNew.$submitted) && formNew.billing_currency_id.$invalid }">
    <label class="col-lg-3 control-label text-right">Billing Currency <span class="required-field glyphicon glyphicon-asterisk" /></label>
    <div class="col-lg-8">
        <select class="form-control" name="billing_currency_id" ng-model="mainList.newItem.billing_currency_id"
                ng-options="country.id as country.currency_name for country in mainList.countries" required>
            <option value="">-- select a currency --</option>
        </select>
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="formNew.billing_currency_id.$error" ng-if="formNew.$submitted || formNew.billing_currency_id.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>


