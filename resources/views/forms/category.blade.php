<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
     ng-class="{ 'has-error': (dataForm.category_id.$touched || dataForm.$submitted) && dataForm.category_id.$invalid }">
    <label class="col-lg-3 control-label text-right">Category <span class="required-field glyphicon glyphicon-asterisk" /></label>
    <div class="col-lg-8">
        <select class="form-control" name="category_id" ng-model="item.category_id"
                ng-options="category.id as category.name for category in mainList.categories" required>
        </select>
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="dataForm.category_id.$error" ng-if="dataForm.$submitted || dataForm.category_id.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>


