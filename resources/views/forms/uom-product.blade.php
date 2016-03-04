<div class="row uom-container col-lg-12">
    @foreach( $uom as $key => $name)
        <div class="row col-lg-2 col-md-2 col-sm-2 col-xs-2 form-group" ng-show="item.product_type.{{ $name }}_active == true"
             ng-class="{ 'has-error': (dataForm.{{ $name }}.$touched || dataForm.$submitted) && dataForm.{{ $name }}.$invalid }">
            <label class="control-label text-capitalize">
                <span ng-bind="item.product_type.{{ $name }}"></span>&nbsp;
                <span class="required-field glyphicon glyphicon-asterisk" ng-show="item.product_type.{{ $name }}_active == true"></span>
            </label>
            <div class="uom">
                <input type="number" class="form-control" name="{{ $name }}"
                       placeholder="" ng-model="item.{{ $name }}" ng-required="item.product_type.{{ $name }}_active"
                       @if($key == 1 ) disabled="disabled"@endif>
                <!-- ngMessages goes here -->
                <div class="help-block ng-messages" ng-messages="dataForm.{{ $name }}.$error" ng-if="dataForm.$submitted || dataForm.{{ $name }}.$touched">
                    @include('layouts.validate-message')
                </div>
            </div>
        </div>
    @endforeach
</div>