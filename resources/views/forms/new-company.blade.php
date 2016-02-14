<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
     ng-class="{ 'has-error': (formNew.company_id.$touched || formNew.$submitted) && formNew.company_id.$invalid }">
    <label class="col-lg-3 control-label text-right">Company <span class="required-field glyphicon glyphicon-asterisk" /></label>
    <div class="col-lg-8">
        <select class="form-control" name="company_id" ng-model="mainList.newItem.company_id"
                ng-options="company.id as company.short_name for company in mainList.companies" required>
            <option value="">-- select a company --</option>
        </select>
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="formNew.company_id.$error" ng-if="formNew.$submitted || formNew.company_id.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>


