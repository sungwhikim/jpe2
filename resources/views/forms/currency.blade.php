<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group">
    <label class="col-lg-3 control-label text-right">Billing Currency</label>
    <div class="col-lg-8">
        <select class="form-control" name="billing_country_id" ng-model="item.billing_country_id"
                ng-options="country.id as country.currency_name for country in mainList.countries">
        </select>
    </div>
</div>


