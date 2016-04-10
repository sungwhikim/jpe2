<div class="row col-lg-3 col-md-3 col-sm-12 col-xs-12 form-group" id="tx-note-container"
     ng-class="{ 'has-error': (txForm.note.$touched || txForm.$submitted) && txForm.note.$invalid }">
    <label class="col-lg-3 control-label">Note</label>
    <div class="col-lg-12 no-float">
        <textarea class="form-control" id="tx-note" name="note" placeholder="Note"
               ng-model="txItem.note" ng-maxlength="500"
               ng-model-options="{ updateOn: 'blur' }">
        </textarea>
        <!-- ngMessages goes here -->
        <div class="help-block ng-messages" ng-messages="txForm.note.$error"
             ng-if="txForm.$submitted || txForm.note.$touched">
            @include('layouts.validate-message')
        </div>
    </div>
</div>