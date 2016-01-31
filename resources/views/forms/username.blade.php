<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
     ng-class="{ 'has-error': (dataForm.username.$touched || dataForm.$submitted) && dataForm.username.$invalid }">
    <label class="col-lg-3 control-label text-right">User Name <span class="required-field glyphicon glyphicon-asterisk" /></label>
    <div class="col-lg-8">
        <input type="text" class="form-control" name="username" placeholder="Username"
               ng-model="item.username" ng-maxlength="{{ $size or 100 }}"
               ng-model-options="{ updateOn: 'blur' }" required>
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="dataForm.username.$error"
             ng-if="dataForm.$submitted || dataForm.username.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>