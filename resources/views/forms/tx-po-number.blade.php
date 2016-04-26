<div class="row col-lg-8 col-md-8 col-sm-8 col-xs-8 form-group"
     ng-class="{ 'has-error': (txForm.po_number.$touched || txForm.$submitted) && txForm.po_number.$invalid }">
    <label class="col-lg-3 control-label">PO Number <span class="required-field glyphicon glyphicon-asterisk"></span></label>
    <div class="col-lg-12 no-float">
        <input type="text" class="form-control" name="po_number" placeholder="PO Number"
               ng-model="txCtrl.txData.po_number" ng-maxlength="250" ng-blur="txCtrl.checkPoNumber()" required>
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="txForm.po_number.$error" ng-if="txForm.$submitted || txForm.po_number.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>


