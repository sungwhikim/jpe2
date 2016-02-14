<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
     ng-class="{ 'has-error': (dataForm.short_name.$touched || dataForm.$submitted) && dataForm.short_name.$invalid }">
    <label class="col-lg-3 control-label text-right">Short Name <span class="required-field glyphicon glyphicon-asterisk" /></label>
    <div class="col-lg-8">
        <input type="text" class="form-control" name="short_name" placeholder="Short Name"
               ng-model="item.short_name" ng-maxlength="{{ $size or 50 }}"
               ng-model-options="{ updateOn: 'blur' }" required>
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="dataForm.short_name.$error"
             ng-if="dataForm.$submitted || dataForm.short_name.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>