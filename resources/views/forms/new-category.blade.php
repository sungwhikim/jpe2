<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
     ng-class="{ 'has-error': (formNew.category_id.$touched || formNew.$submitted) && formNew.category_id.$invalid }">
    <label class="col-lg-3 control-label text-right">Category <span class="required-field glyphicon glyphicon-asterisk" /></label>
    <div class="col-lg-8">
        <select class="form-control" name="category_id" ng-model="mainList.newItem.category_id"
                ng-options="category.id as category.name for category in mainList.categories" required>
            <option value="">-- select a category --</option>
        </select>
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="formNew.category_id.$error" ng-if="formNew.$submitted || formNew.category_id.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>


