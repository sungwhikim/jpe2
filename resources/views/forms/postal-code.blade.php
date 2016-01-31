<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
     ng-class="{ 'has-error': (dataForm.postal_code.$touched || dataForm.$submitted) && dataForm.postal_code.$invalid }">
    <label class="col-lg-3 control-label text-right">Postal Code <span class="required-field glyphicon glyphicon-asterisk" /></label>
    <div class="col-lg-8">
        <input type="text" class="form-control" name="postal_code" placeholder="Postal Code"
               ng-model="item.postal_code" ng-maxlength="30"
               ng-model-options="{ updateOn: 'blur' }" required>
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="dataForm.postal_code.$error"
             ng-if="dataForm.$submitted || dataForm.postal_code.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>