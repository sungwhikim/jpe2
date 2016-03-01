<div class="row uom-container col-lg-12">
    @foreach( $uom as $key => $name)
        <div class="row col-lg-2 col-md-2 col-sm-2 col-xs-2 form-group" ng-show="mainList.newItem.product_type.{{ $name }}_active == true"
             ng-class="{ 'has-error': (formNew.{{ $name }}.$touched || formNew.$submitted) && formNew.{{ $name }}.$invalid }">
            <label class="control-label text-capitalize"><span ng-bind="mainList.newItem.product_type.{{ $name }}"></span> <span class="required-field glyphicon glyphicon-asterisk" /></label>
            <div class="uom">
                <input type="number" class="form-control" name="{{ $name }}"
                       placeholder="" ng-model="mainList.newItem.{{ $name }}" required>
                <!-- ngMessages goes here -->
                <div class="help-block ng-messages" ng-messages="formNew.{{ $name }}.$error" ng-if="formNew.$submitted || formNew.{{ $name }}.$touched">
                    @include('layouts.validate-message')
                </div>
            </div>
        </div>
    @endforeach
</div>