<div class="report-date report-control"
     ng-class="{ 'has-error': (reportForm.{{ $name }}.$touched || reportForm.$submitted) && reportForm.{{ $name }}.$invalid }">
    <label class="control-label">{{ $title }} <span class="required-field glyphicon glyphicon-asterisk" /></label>
    <div ng-class="{ 'has-error': (reportForm.{{ $name }}.$touched || reportForm.$submitted) && reportForm.{{ $name }}.$invalid }">
        <div class="input-group" style="width: 175px;">
            <input type="text" class="form-control" name="{{ $name }}" ng-model="ctrl.criteria.{{ $name }}"
                   uib-datepicker-popup="@{{ ctrl.datePicker2.dateFormat }}" is-open="ctrl.datePicker2.popupOpened"
                   datepicker-options="ctrl.datePicker2.dateOptions" on-open-focus="false" close-text="Close" required />
            <span class="input-group-btn">
            <button type="button" class="btn btn-default btn-date" ng-click="ctrl.datePicker2.open()">
                <i class="glyphicon glyphicon-calendar"></i>
            </button>
            </span>
        </div>
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="reportForm.{{ $name }}.$error"
             ng-if="reportForm.$submitted || reportForm.{{ $name }}.$touched" ng-cloak>
            @include('layouts.validate-message')
        </div>
    </div>
</div>