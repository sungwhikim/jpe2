<div class="row col-lg-5 col-md-5 col-sm-5 col-xs-5 form-group"
     ng-class="{ 'has-error': (txForm.tx_date.$touched || txForm.$submitted) && txForm.tx_date.$invalid }">
    <label class="col-lg-3 control-label">Date <span class="required-field glyphicon glyphicon-asterisk" /></label>
    <div class="col-lg-12 no-float">
        <div ng-class="{ 'has-error': (txForm.tx_date.$touched || txForm.$submitted) && txForm.tx_date.$invalid }">
            <div class="input-group">
                <input type="text" class="form-control" name="tx_date" ng-model="txItem.tx_date"
                       uib-datepicker-popup="@{{ txCtrl.datePicker.dateFormat }}"
                       is-open="txCtrl.datePicker.popupOpened"
                       datepicker-options="txCtrl.datePicker.dateOptions"
                       on-open-focus="false" close-text="Close" required />
                <span class="input-group-btn">
                <button type="button" class="btn btn-default" ng-click="txCtrl.datePicker.open()">
                    <i class="glyphicon glyphicon-calendar"></i>
                </button>
                </span>
            </div>
            <!-- ngMessages goes here -->
            <div class="help-block ng-messages" ng-messages="txForm.tx_date.$error"
                 ng-if="txForm.$submitted || txForm.tx_date.$touched">
                @include('layouts.validate-message')
            </div>
        </div>
    </div>
</div>