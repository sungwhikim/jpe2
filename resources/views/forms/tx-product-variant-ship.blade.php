<div class="new-variant-container-ship">
    <div class="col-lg-2 col-md-2 col-sm-2 form-group variant-container" ng-class="{ 'has-error': txCtrl.newItem.variant1_error }"
         ng-show="txCtrl.newItem.variants.variant1_active == true">
        <label class="col-lg-3 control-label">@{{ txCtrl.newItem.variants.variant1_name }} <span class="required-field glyphicon glyphicon-asterisk"></span></label>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-float">
            <div class="input-group">
                <input type="text" class="form-control" name="variant1" placeholder="@{{ txCtrl.newItem.variants.variant1_name }}"
                       ng-model="txCtrl.newItem.variant1_value" readonly required>
                <div class="input-group-btn">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-select-col-2" ng-show="txCtrl.newItem.variants.variant1_variants.length > 0">
                        <li><a ng-click="txCtrl.selectVariantShip(txCtrl.newItem, 'variant1', txCtrl.variantNone)">[none]</a></li>
                        <li ng-repeat="variant in txCtrl.newItem.variants.variant1_variants">
                            <a ng-click="txCtrl.selectVariantShip(txCtrl.newItem, 'variant1', variant)">@{{ variant.value }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-2 col-md-2 col-sm-2 form-group variant-container" ng-class="{ 'has-error': txCtrl.newItem.variant2_error }"
         ng-show="txCtrl.newItem.variants.variant2_active == true">
        <label class="col-lg-3 control-label">@{{ txCtrl.newItem.variants.variant2_name }} <span class="required-field glyphicon glyphicon-asterisk"></span></label>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-float">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="@{{ txCtrl.newItem.variants.variant2_name }}"
                       ng-model="txCtrl.newItem.variant2_value" readonly required>
                <div class="input-group-btn">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-select-col-2" ng-show="txCtrl.newItem.variants.variant2_variants.length > 0">
                        <li><a ng-click="txCtrl.selectVariantShip(txCtrl.newItem, 'variant2', txCtrl.variantNone)">[none]</a></li>
                        <li ng-repeat="variant in txCtrl.newItem.variants.variant2_variants">
                            <a ng-click="txCtrl.selectVariantShip(txCtrl.newItem, 'variant2', variant)">@{{ variant.value }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-2 col-md-2 col-sm-2 form-group variant-container" ng-class="{ 'has-error': txCtrl.newItem.variant3_error }"
         ng-show="txCtrl.newItem.variants.variant3_active == true">
        <label class="col-lg-3 control-label">@{{ txCtrl.newItem.variants.variant3_name }} <span class="required-field glyphicon glyphicon-asterisk"></span></label>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-float">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="@{{ txCtrl.newItem.variants.variant3_name }}"
                       ng-model="txCtrl.newItem.variant3_value" readonly required>
                <div class="input-group-btn">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-select-col-2" ng-show="txCtrl.newItem.variants.variant3_variants.length > 0">
                        <li><a ng-click="txCtrl.selectVariantShip(txCtrl.newItem, 'variant3', txCtrl.variantNone)">[none]</a></li>
                        <li ng-repeat="variant in txCtrl.newItem.variants.variant3_variants">
                            <a ng-click="txCtrl.selectVariantShip(txCtrl.newItem, 'variant3', variant)">@{{ variant.value }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-2 col-md-2 col-sm-2 form-group variant-container" ng-class="{ 'has-error': txCtrl.newItem.variant4_error }"
         ng-show="txCtrl.newItem.variants.variant4_active == true">
        <label class="col-lg-3 control-label">@{{ txCtrl.newItem.variants.variant4_name }} <span class="required-field glyphicon glyphicon-asterisk"></span></label>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-float">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="@{{ txCtrl.newItem.variants.variant4_name }}"
                       ng-model="txCtrl.newItem.variant4_value" readonly required>
                <div class="input-group-btn">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-select-col-2" ng-show="txCtrl.newItem.variants.variant4_variants.length > 0">
                        <li><a ng-click="txCtrl.selectVariantShip(txCtrl.newItem, 'variant4', txCtrl.variantNone)">[none]</a></li>
                        <li ng-repeat="variant in txCtrl.newItem.variants.variant4_variants">
                            <a ng-click="txCtrl.selectVariantShip(txCtrl.newItem, 'variant4', variant)">@{{ variant.value }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>