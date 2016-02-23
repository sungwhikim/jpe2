<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
     ng-class="{ 'has-error': (formNew.product_type_id.$touched || formNew.$submitted) && formNew.product_type_id.$invalid }">
    <label class="col-lg-3 control-label text-right">{{ $default or '' }} Product Type <span class="required-field glyphicon glyphicon-asterisk" /></label>
    <div class="col-lg-8">
        <select class="form-control" name="product_type_id" ng-model="mainList.newItem.product_type_id"
                ng-options="product_type.id as product_type.name for product_type in mainList.product_types" required>
            <option value="">-- select a product type --</option>
        </select>
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="formNew.product_type_id.$error" ng-if="formNew.$submitted || formNew.product_type_id.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>


