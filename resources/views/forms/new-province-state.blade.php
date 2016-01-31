<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
     ng-class="{ 'has-error': (formNew.country_id.$touched || formNew.$submitted) && formNew.country_id.$invalid }">
    <label class="col-lg-3 control-label text-right">Country <span class="required-field glyphicon glyphicon-asterisk" /></label>
    <div class="col-lg-8">
        <select class="form-control" name="country_id" ng-model="mainList.newItem.country_id"
                ng-change="formNew.province_id=null" ng-options="country.id as country.name for country in mainList.countries" required>
            <option value="">-- select a country --</option>
        </select>
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="formNew.country_id.$error" ng-if="formNew.$submitted || formNew.country_id.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>
<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
     ng-class="{ 'has-error': (formNew.province_id.$touched || formNew.$submitted) && formNew.province_id.$invalid }">
    <label class="col-lg-3 control-label text-right">Province/State <span class="required-field glyphicon glyphicon-asterisk" /></label>
    <div class="col-lg-8">
        <select class="form-control" name="province_id" ng-model="mainList.newItem.province_id" required
                ng-options="province.id as province.name for province in (mainList.countries | filter:{id:mainList.newItem.country_id})[0].provinces">
            <option value="">-- select a province/state --</option>
        </select>
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="formNew.province_id.$error"
             ng-if="formNew.$submitted || formNew.province_id.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>