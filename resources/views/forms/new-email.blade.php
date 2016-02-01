<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
     ng-class="{ 'has-error': (formNew.email.$touched || formNew.$submitted) && formNew.email.$invalid }">
    <label class="col-lg-3 control-label text-right">Email @if( !empty($required) ) <span class="required-field glyphicon glyphicon-asterisk" />@endif</label>
    <div class="col-lg-8">
        <input type="email" class="form-control" name="email" placeholder="Email"
               ng-model="mainList.newItem.email" ng-maxlength="100"
               ng-model-options="{ updateOn: 'blur' }" {{ $required or '' }}>
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="formNew.email.$error"
             ng-if="formNew.$submitted || formNew.email.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>