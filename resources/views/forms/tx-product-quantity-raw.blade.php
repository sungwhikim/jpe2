<div class="input-group">
    <div class="input-group-btn">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-left">
            <li ng-repeat="uom in {{ $model_object }}.uoms">
                <a ng-click="{{ $controller }}.selectUom({{ $model_object }}, uom)">@{{ uom.name }}</a>
            </li>
        </ul>
    </div>
    <input type="number" class="form-control" placeholder="Quantity" ng-model="{{ $model_object }}.quantity">
</div>