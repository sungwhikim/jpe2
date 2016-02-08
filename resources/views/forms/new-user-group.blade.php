<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
     ng-class="{ 'has-error': (formNew.user_group_id.$touched || formNew.$submitted) && formNew.user_group_id.$invalid }">
    <label class="col-lg-3 control-label text-right">User Group <span class="required-field glyphicon glyphicon-asterisk" /></label>
    <div class="col-lg-8">
        <select class="form-control" name="user_group_id" ng-model="mainList.newItem.user_group_id"
                ng-options="user_group.id as user_group.name for user_group in mainList.user_groups" required>
            <option value="">-- select a user group --</option>
        </select>
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="formNew.user_group_id.$error" ng-if="formNew.$submitted || formNew.user_group_id.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>


