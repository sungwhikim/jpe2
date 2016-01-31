<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
     ng-class="{ 'has-error': (dataForm.address2.$touched || dataForm.$submitted) && dataForm.address2.$invalid }">
    <label class="col-lg-3 control-label text-right">Address 2</label>
    <div class="col-lg-8">
        <input type="text" class="form-control" name="address2" placeholder="Address 2"
               ng-model="item.address2" ng-maxlength="100"
               ng-model-options="{ updateOn: 'blur' }">
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="dataForm.address2.$error"
             ng-if="dataForm.$submitted || dataForm.address2.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>