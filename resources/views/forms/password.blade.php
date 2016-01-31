<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
     ng-class="{ 'has-error': (dataForm.email.$touched || dataForm.$submitted) && dataForm.email.$invalid }">
    <label class="col-lg-3 control-label text-right">Email</label>
    <div class="col-lg-8">
        <input type="email" class="form-control" name="email" placeholder="Email"
               ng-model="item.email" ng-maxlength="100"
               ng-model-options="{ updateOn: 'blur' }" {{ $required or '' }}>
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="dataForm.email.$error"
             ng-if="dataForm.$submitted || dataForm.email.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>