<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group">
    <label class="col-lg-3 control-label text-right">Country <span class="required-field glyphicon glyphicon-asterisk" /></label>
    <div class="col-lg-8">
        <select class="form-control" name="country_id" ng-model="item.country_id" ng-change="item.province_id=null"
                ng-options="country.id as country.name for country in mainList.countries">
        </select>
    </div>
</div>
<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
     ng-class="{ 'has-error': (dataForm.province_id.$touched || dataForm.$submitted) && dataForm.province_id.$invalid }">
    <label class="col-lg-3 control-label text-right">Province/State <span class="required-field glyphicon glyphicon-asterisk" /></label>
    <div class="col-lg-8">
        <select class="form-control" name="province_id" ng-model="item.province_id" required
                ng-options="province.id as province.name for province in (mainList.countries | filter:{id:item.country_id})[0].provinces">
        </select>
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="dataForm.province_id.$error"
             ng-if="dataForm.$submitted || dataForm.province_id.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>