<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
     ng-class="{ 'has-error': (formNew.sort_order.$touched || formNew.$submitted) && formNew.sort_order.$invalid }">
    <label class="col-lg-3 control-label text-right">Sort Order <span class="required-field glyphicon glyphicon-asterisk" /></label>
    <div class="col-lg-8">
        <input type="number" class="form-control" name="sort_order" placeholder="0"
               ng-model="mainList.newItem.sort_order" ng-maxlength="10"
               ng-model-options="{ updateOn: 'blur' }" required>
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="formNew.sort_order.$error"
             ng-if="formNew.$submitted || formNew.sort_order.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>