<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
     ng-class="{ 'has-error': (formNew.username.$touched || formNew.$submitted) && formNew.username.$invalid }">
    <label class="col-lg-3 control-label text-right">User Name <span class="required-field glyphicon glyphicon-asterisk" /></label>
    <div class="col-lg-8">
        <input type="text" class="form-control" name="username" placeholder="Username"
               ng-model="mainList.newItem.username" ng-maxlength="{{ $size or 20 }}"
               ng-model-options="{ updateOn: 'blur' }" required>
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="formNew.username.$error"
             ng-if="formNew.$submitted || formNew.username.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>