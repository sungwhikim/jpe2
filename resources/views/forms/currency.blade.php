<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
     ng-class="{ 'has-error': (dataForm.billing_country_id.$touched || dataForm.$submitted) && dataForm.billing_country_id.$invalid }">
    <label class="col-lg-3 control-label text-right">Billing Currency <span class="required-field glyphicon glyphicon-asterisk" /></label>
    <div class="col-lg-8">
        <select class="form-control" name="billing_country_id" ng-model="item.billing_country_id"
                ng-options="country.id as country.currency_name for country in mainList.countries" required>
        </select>
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="dataForm.billing_country_id.$error" ng-if="dataForm.$submitted || dataForm.billing_country_id.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>


