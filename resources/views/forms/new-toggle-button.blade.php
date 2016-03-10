<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
     ng-class="{ 'has-error': (formNew.{{ $name }}.$touched || formNew.$submitted) && formNew.{{ $name }}.$invalid }">
    <label class="col-lg-3 control-label text-right">{{ $title }}&nbsp;
        @if( $required === true )<span class="required-field glyphicon glyphicon-asterisk"></span>@endif
    </label>
    <div class="col-lg-3 col-md-4 col-sm-5 col-xs-4">
        <div class="checkbox checkbox-slider--b checkbox-slider-md">
            <label>
                <input type="checkbox" name="{{ $name }}" value="true"
                       ng-init="mainList.newItem.{{ $name }} = {{ $default }}"
                       ng-model="mainList.newItem.{{ $name }}">
                <span></span>
            </label>
        </div>
    </div>
</div>