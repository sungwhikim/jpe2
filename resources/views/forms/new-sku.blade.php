<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
     ng-class="{ 'has-error': (formNew.sku.$touched || formNew.$submitted) && formNew.sku.$invalid }">
    <label class="col-lg-2 control-label">SKU <span class="required-field glyphicon glyphicon-asterisk" /></label>
    <div class="col-lg-8">
        <input type="text" class="form-control" name="sku"
               placeholder="Code" ng-model="mainList.newItem.sku"
               ng-maxlength="{{ $size or 100 }}"
               required>
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="formNew.sku.$error" ng-if="formNew.$submitted || formNew.sku.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>