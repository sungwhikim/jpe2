<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
     ng-class="{ 'has-error': (dataForm.name.$touched || dataForm.$submitted) && dataForm.name.$invalid }">
    <label class="col-lg-3 control-label text-right">Name <span class="required-field glyphicon glyphicon-asterisk" /></label>
    <div class="col-lg-8">
        <input type="text" class="form-control" name="name" placeholder="Name"
               ng-model="item.name" ng-maxlength="{{ $size or 100 }}"
               ng-model-options="{ updateOn: 'blur' }" required>
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="dataForm.name.$error"
             ng-if="dataForm.$submitted || dataForm.name.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>