<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
     ng-class="{ 'has-error': (dataForm.care_of.$touched || dataForm.$submitted) && dataForm.care_of.$invalid }">
    <label class="col-lg-3 control-label text-right">C/O</label>
    <div class="col-lg-8">
        <input type="text" class="form-control" name="care_of" placeholder="C/O"
               ng-model="item.care_of" ng-maxlength="100"
               ng-model-options="{ updateOn: 'blur' }">
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="dataForm.care_of.$error"
             ng-if="dataForm.$submitted || dataForm.care_of.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>