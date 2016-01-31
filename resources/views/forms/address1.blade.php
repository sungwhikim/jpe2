<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
     ng-class="{ 'has-error': (dataForm.address1.$touched || dataForm.$submitted) && dataForm.address1.$invalid }">
    <label class="col-lg-3 control-label text-right">Address 1 <span class="required-field glyphicon glyphicon-asterisk" /></label>
    <div class="col-lg-8">
        <input type="text" class="form-control" name="address1" placeholder="Address 1"
               ng-model="item.address1" ng-maxlength="100"
               ng-model-options="{ updateOn: 'blur' }" required>
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="dataForm.address1.$error"
             ng-if="dataForm.$submitted || dataForm.address1.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>