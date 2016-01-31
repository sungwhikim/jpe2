<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
     ng-class="{ 'has-error': (dataForm.contact.$touched || dataForm.$submitted) && dataForm.contact.$invalid }">
    <label class="col-lg-3 control-label text-right">Contact</label>
    <div class="col-lg-8">
        <input type="text" class="form-control" name="contact" placeholder="Contact"
               ng-model="item.contact" ng-maxlength="100"
               ng-model-options="{ updateOn: 'blur' }">
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="dataForm.contact.$error"
             ng-if="dataForm.$submitted || dataForm.contact.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>