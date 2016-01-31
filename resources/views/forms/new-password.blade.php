<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6">
    <div ng-class="{ 'has-error': (formNew.password.$touched || formNew.$submitted) && formNew.password.$invalid }">
        <label class="col-lg-3 control-label text-right">Password</label>
        <div class="col-lg-8" style="margin-bottom: 10px;">
            <input type="password" class="form-control" name="password"
                   ng-model="mainList.newItem.password" ng-maxlength="30" ng-minlength="6"
                   autocomplete="off" required>
            <!-- ngMessages goes here -->
            <div class="help-block ng-messages" ng-messages="formNew.password.$error"
                 ng-if="formNew.$submitted || formNew.password.$touched">
                @include('layouts.validate-message-password')
            </div>
        </div>
    </div>
    <div ng-class="{ 'has-error': (formNew.password_validate.$touched || formNew.$submitted) && formNew.password_validate.$invalid }">
        <label class="col-lg-3 control-label text-right">Validate Password</label>
        <div class="col-lg-8">
            <input type="password" class="form-control" name="password_validate"
                   ng-model="mainList.newItem.password_validate" ng-maxlength="30" ng-minlength="6"
                   autocomplete="off" required compare-to="mainList.newItem.password">
            <!-- ngMessages goes here -->
            <div class="help-block ng-messages" ng-messages="formNew.password_validate.$error"
                 ng-if="formNew.$submitted || formNew.password_validate.$touched">
                @include('layouts.validate-message-password')
            </div>
        </div>
    </div>
</div>