<div ng-class="{ 'has-error': ({{ $form_name }}.{{ $name }}.$touched || {{ $form_name }}.$submitted) && {{ $form_name }}.{{ $name }}.$invalid }">
    <div class="input-group">
        <input type="text" class="form-control" name="{{ $name }}" ng-model="{{ $model_name }}"
               uib-datepicker-popup="@{{ mainList.datePicker.dateFormat }}"
               is-open="mainList.datePicker.popupOpened"
               datepicker-options="mainList.datePicker.dateOptions"
               on-open-focus="false" close-text="Close" @if( $required == true ) ng-required="true" required @endif />
                                                        <span class="input-group-btn">
                                                        <button type="button" class="btn btn-default" ng-click="mainList.datePicker.open()">
                                                            <i class="glyphicon glyphicon-calendar"></i>
                                                        </button>
                                                        </span>
    </div>
    <!-- ngMessages goes here -->
    <div class="help-block ng-messages" ng-messages="{{ $form_name }}.{{ $name }}.$error"
         ng-if="{{ $form_name }}.$submitted || {{ $form_name }}.{{ $name }}.$touched">
        @include('layouts.validate-message')
    </div>
</div>
