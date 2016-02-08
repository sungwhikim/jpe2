<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
     ng-class="{ 'has-error': (dataForm.url.$touched || dataForm.$submitted) && dataForm.url.$invalid }">
    <label class="col-lg-3 control-label text-right">Url <span class="required-field glyphicon glyphicon-asterisk" /></label>
    <div class="col-lg-8">
        <input type="text" class="form-control" name="url" placeholder="Url"
               ng-model="item.url" ng-maxlength="100" maxlength="100"
               ng-model-options="{ updateOn: 'blur' }" required>
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="dataForm.url.$error"
             ng-if="dataForm.$submitted || dataForm.url.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>