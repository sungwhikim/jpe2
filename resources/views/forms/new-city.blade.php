<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
     ng-class="{ 'has-error': (formNew.city.$touched || formNew.$submitted) && formNew.city.$invalid }">
    <label class="col-lg-3 control-label text-right">City <span class="required-field glyphicon glyphicon-asterisk" /></label>
    <div class="col-lg-8">
        <input type="text" class="form-control" name="city" placeholder="City"
               ng-model="mainList.newItem.city" ng-maxlength="50"
               ng-model-options="{ updateOn: 'blur' }" required>
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="formNew.city.$error"
             ng-if="formNew.$submitted || formNew.city.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>