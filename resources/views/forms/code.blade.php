<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
     ng-class="{ 'has-error': (dataForm.code.$touched || dataForm.$submitted) && dataForm.code.$invalid }">
    <label class="col-lg-3 control-label">Code <span class="required-field glyphicon glyphicon-asterisk" /></label>
    <div class="col-lg-8">
        <input type="text" class="form-control" name="code" placeholder="Code"
               ng-model="item.code"
               ng-minlength="{{ $size or 2 }}" ng-maxlength="{{ $size or 2 }}"
               required>
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="dataForm.code.$error"
             ng-if="dataForm.$submitted || dataForm.code.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>