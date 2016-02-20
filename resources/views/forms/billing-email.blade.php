<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group" style="height: 100px !important;"
     ng-class="{ 'has-error': (dataForm.billing_email.$touched || dataForm.$submitted) && dataForm.billing_email.$invalid }">
    <label class="col-lg-3 control-label text-right">Billing Email</label>
    <div class="col-lg-8">
        <textarea class="form-control" name="billing_email" placeholder="Billing Email"
               ng-model="item.billing_email" ng-maxlength="1000"
               ng-model-options="{ updateOn: 'blur' }" rows="3">
        </textarea>
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="dataForm.billing_email.$error"
             ng-if="dataForm.$submitted || dataForm.billing_email.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>