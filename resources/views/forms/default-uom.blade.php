<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
     ng-class="{ 'has-error': (dataForm.default_uom.$touched || dataForm.$submitted) && dataForm.default_uom.$invalid }">
    <label class="col-lg-3 control-label text-right">Default UOM <span class="required-field glyphicon glyphicon-asterisk" /></label>
    <div class="col-lg-8">
        <select class="form-control" name="default_uom" ng-model="item.default_uom"
                ng-options="default_uom.name as default_uom.name for default_uom in mainList.default_uom_list" required>
        </select>
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="dataForm.default_uom.$error" ng-if="dataForm.$submitted || dataForm.default_uom.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>


