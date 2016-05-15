<div class="row uom-container col-lg-12 col-md-12 col-sm-12">
    @foreach( $uom as $key => $name)
        <div class="row col-lg-2 col-md-2 col-sm-2 col-xs-2 form-group" ng-show="mainList.newItem.product_type.{{ $name }}_active == true"
             ng-class="{ 'has-error': (formNew.{{ $name }}.$touched || formNew.$submitted) && formNew.{{ $name }}.$invalid }">
            <label class="control-label text-capitalize">
                <span ng-bind="mainList.newItem.product_type.{{ $name }}"></span>&nbsp;
                <span class="required-field glyphicon glyphicon-asterisk" ng-show="mainList.newItem.product_type.{{ $name }}_active == true"></span>
            </label>
            <div class="uom">
                <input type="number" class="form-control" name="{{ $name }}"
                       placeholder="" ng-model="mainList.newItem.{{ $name }}" ng-required="mainList.newItem.product_type.{{ $name }}_active"
                       @if($key == 1 ) disabled="disabled" ng-init="mainList.newItem.{{ $name }} = 1" value="1" @endif>
                <!-- ngMessages goes here -->
                <div class="help-block ng-messages" ng-messages="formNew.{{ $name }}.$error" ng-if="formNew.$submitted || formNew.{{ $name }}.$touched">
                    @include('layouts.validate-message')
                </div>
            </div>
        </div>
    @endforeach
</div>