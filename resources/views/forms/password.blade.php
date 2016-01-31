<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6">
    <div ng-class="{ 'has-error': (dataForm.password.$touched || dataForm.$submitted) && dataForm.password.$invalid }">
        <label class="col-lg-3 control-label text-right">Password</label>
        <div class="col-lg-8" style="margin-bottom: 10px;">
            <input type="password" class="form-control" name="password"
                   ng-model="item.password" ng-maxlength="30" ng-minlength="6"
                   autocomplete="off">
            <!-- ngMessages goes here -->
            <div class="help-block ng-messages" ng-messages="dataForm.password.$error"
                 ng-if="dataForm.$submitted || dataForm.password.$touched">
                @include('layouts.validate-message-password')
            </div>
        </div>
    </div>
    <div ng-class="{ 'has-error': (dataForm.password_validate.$touched || dataForm.$submitted) && dataForm.password_validate.$invalid }">
        <label class="col-lg-3 control-label text-right">Validate Password</label>
        <div class="col-lg-8">
            <input type="password" class="form-control" name="password_validate"
                   ng-model="item.password_validate" ng-maxlength="30" ng-minlength="6"
                   autocomplete="off" compare-to="item.password">
            <!-- ngMessages goes here -->
            <div class="help-block ng-messages" ng-messages="dataForm.password_validate.$error"
                 ng-if="dataForm.$submitted || dataForm.password_validate.$touched">
                @include('layouts.validate-message-password')
            </div>
        </div>
    </div>
</div>