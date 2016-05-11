<div class="row uom-container col-lg-12">
    <div class="row col-lg-1 col-md-1 col-sm-1 bin-item-container"
         ng-class="{ 'has-error': (formNew.aisle.$touched || formNew.$submitted) && formNew.aisle.$invalid }">
        <label class="control-label text-nowrap">Aisle</label>
        <div class="bin-item aisle">
            <input type="text" class="form-control text-uppercase" name="aisle"
                   placeholder="" ng-model="mainList.newItem.aisle" maxlength="2"
                   ng-minlength="2" ng-maxlength="2" required>
            <!-- ngMessages goes here -->
            <div class="help-block ng-messages" ng-messages="formNew.aisle.$error" ng-if="formNew.$submitted || formNew.aisle.$touched">
                @include('layouts.validate-message')
            </div>
        </div>
    </div>
    <div class="row col-lg-1 col-md-1 col-sm-1 bin-item-container"
         ng-class="{ 'has-error': (formNew.section.$touched || formNew.$submitted) && formNew.section.$invalid }">
        <label class="control-label text-nowrap">Section</label>
        <div class="bin-item aisle">
            <input type="text" class="form-control" name="section"
                   placeholder="" ng-model="mainList.newItem.section" maxlength="2" size="2"
                   ng-maxlength="2" required>
            <!-- ngMessages goes here -->
            <div class="help-block ng-messages" ng-messages="formNew.section.$error" ng-if="formNew.$submitted || formNew.section.$touched">
                @include('layouts.validate-message')
            </div>
        </div>
    </div>
    <div class="row col-lg-1 col-md-1 col-sm-1 bin-item-container"
         ng-class="{ 'has-error': (formNew.tier.$touched || formNew.$submitted) && formNew.tier.$invalid }">
        <label class="control-label text-nowrap">Tier</label>
        <div class="bin-item aisle">
            <input type="text" class="form-control" name="tier"
                   placeholder="" ng-model="mainList.newItem.tier" maxlength="2" size="2"
                   ng-maxlength="2" required>
            <!-- ngMessages goes here -->
            <div class="help-block ng-messages" ng-messages="formNew.tier.$error" ng-if="formNew.$submitted || formNew.tier.$touched">
                @include('layouts.validate-message')
            </div>
        </div>
    </div>
    <div class="row col-lg-1 col-md-1 col-sm-1 bin-item-container"
         ng-class="{ 'has-error': (formNew.position.$touched || formNew.$submitted) && formNew.position.$invalid }">
        <label class="control-label text-nowrap">Position</label>
        <div class="bin-item aisle">
            <input type="text" class="form-control" name="position"
                   placeholder="" ng-model="mainList.newItem.position" maxlength="2" size="2"
                   ng-maxlength="2" required>
            <!-- ngMessages goes here -->
            <div class="help-block ng-messages" ng-messages="formNew.position.$error" ng-if="formNew.$submitted || formNew.position.$touched">
                @include('layouts.validate-message')
            </div>
        </div>
    </div>
</div>