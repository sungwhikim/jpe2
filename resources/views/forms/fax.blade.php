<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
     ng-class="{ 'has-error': (dataForm.fax.$touched || dataForm.$submitted) && dataForm.fax.$invalid }">
    <label class="col-lg-3 control-label text-right">Fax</label>
    <div class="col-lg-8">
        <input type="text" class="form-control" name="fax" placeholder="Fax"
               ng-model="item.fax" ng-maxlength="30"
               ng-model-options="{ updateOn: 'blur' }">
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="dataForm.fax.$error"
             ng-if="dataForm.$submitted || dataForm.fax.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>