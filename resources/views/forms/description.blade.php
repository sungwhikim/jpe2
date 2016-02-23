<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
     ng-class="{ 'has-error': (dataForm.description.$touched || dataForm.$submitted) && dataForm.description.$invalid }">
    <label class="col-lg-3 control-label text-right">Description </label>
    <div class="col-lg-8">
        <input type="text" class="form-control" name="description" placeholder="Description"
               ng-model="item.description" ng-maxlength="{{ $size or 100 }}"
               ng-model-options="{ updateOn: 'blur' }">
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="dataForm.description.$error"
             ng-if="dataForm.$submitted || dataForm.description.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>