<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
     ng-class="{ 'has-error': (formNew.code.$touched || formNew.$submitted) && formNew.code.$invalid }">
    <label class="col-lg-2 control-label">Code <span class="required-field glyphicon glyphicon-asterisk" /></label>
    <div class="col-lg-8">
        <input type="text" class="form-control" name="code"
               placeholder="Code" ng-model="mainList.newItem.code"
               ng-minlength="{{ $size or 2 }}"
               ng-maxlength="{{ $size or 2 }}"
               required>
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="formNew.code.$error" ng-if="formNew.$submitted || formNew.code.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>