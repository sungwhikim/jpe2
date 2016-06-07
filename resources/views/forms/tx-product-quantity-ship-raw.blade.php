<div class="input-group" style="width: 172px;">
    <div class="input-group-btn">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-left" ng-show="{{ $model_object }}.uoms.length > 0">
            <li ng-repeat="uom in {{ $model_object }}.uoms"
                ng-class="{active: uom.key == {{ $model_object }}.selectedUom}">
                <a ng-click="txCtrl.selectUom({{ $model_object }}, uom)">@{{ uom.name }}</a>
            </li>
        </ul>
    </div>
    <input type="number" class="form-control" placeholder="Quantity" style="width: 100px;"
           ng-model="{{ $model_object }}.quantity" min="1">
    <div class="input-group-btn">
        <button class="btn btn-default" style="min-width: 75px;">
            &nbsp;<span id="item-total" class="badge" ng-bind="{{ $model_object }}.inventoryTotal"></span>
        </button>
    </div>
</div>
