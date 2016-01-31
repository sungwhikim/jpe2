<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
     ng-class="{ 'has-error': (formNew.fax.$touched || formNew.$submitted) && formNew.fax.$invalid }">
    <label class="col-lg-3 control-label text-right">Fax</label>
    <div class="col-lg-8">
        <input type="text" class="form-control" name="fax" placeholder="Fax"
               ng-model="mainList.newItem.fax" ng-maxlength="30"
               ng-model-options="{ updateOn: 'blur' }">
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="formNew.fax.$error"
             ng-if="formNew.$submitted || formNew.fax.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>