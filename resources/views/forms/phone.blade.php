<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
     ng-class="{ 'has-error': (dataForm.phone.$touched || dataForm.$submitted) && dataForm.phone.$invalid }">
    <label class="col-lg-3 control-label text-right">Phone</label>
    <div class="col-lg-8">
        <input type="text" class="form-control" name="phone" placeholder="Phone"
               ng-model="item.phone" ng-maxlength="30"
               ng-model-options="{ updateOn: 'blur' }">
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="dataForm.phone.$error"
             ng-if="dataForm.$submitted || dataForm.phone.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>