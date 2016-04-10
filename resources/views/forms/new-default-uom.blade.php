<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
     ng-class="{ 'has-error': (formNew.default_uom.$touched || formNew.$submitted) && formNew.default_uom.$invalid }">
    <label class="col-lg-3 control-label text-right">Default UOM <span class="required-field glyphicon glyphicon-asterisk" /></label>
    <div class="col-lg-8">
        <select class="form-control" name="default_uom" ng-model="mainList.newItem.default_uom"
                ng-options="default_uom.name as default_uom.name for default_uom in mainList.default_uom_list" required>
            <option value="">-- please select a default UOM --</option>
        </select>
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="formNew.default_uom.$error" ng-if="formNew.$submitted || formNew.default_uom.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>


