<div class="input-group col-lg-4 col-md-4 col-sm-4 col-xs-3 pull-left">
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
    <input type="number" class="form-control" placeholder="Qty" style="width: 75px;" ng-change="txCtrl.changeQty({{ $model_object }})"
           ng-model="{{ $model_object }}.quantity" min="0">
    <div class="input-group-btn">
        <div class="inventory-quantity-container">
            <div id="item-total" class="badge-inventory"
                  ng-bind="({{ $model_object }}.inventoryTotal / {{ $model_object }}.selectedUomMultiplierTotal) - {{ $model_object }}.quantity | rounded:1"></div>
            @if( isset($show_ship_bin) )
                <div class="glyphicon glyphicon-download-alt btn-ship-bin" ng-show="txCtrl.txSetting.new == false"
                     ng-click="txCtrl.showShipBin({{ $model_object }})"></div>
            @endif
        </div>
    </div>
</div>
