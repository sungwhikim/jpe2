<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
     ng-class="{ 'has-error': (dataForm.product_type_id.$touched || dataForm.$submitted) && dataForm.product_type_id.$invalid }">
    <label class="col-lg-3 control-label text-right">{{ $default or '' }} Product Type <span class="required-field glyphicon glyphicon-asterisk" /></label>
    <div class="col-lg-8">
        <select class="form-control" name="product_type_id" ng-model="item.product_type_id"
                ng-options="product_type.id as product_type.name for product_type in mainList.product_types" required>
        </select>
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="dataForm.product_type_id.$error" ng-if="dataForm.$submitted || dataForm.product_type_id.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>


