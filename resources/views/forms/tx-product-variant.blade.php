<div class="col-lg-2 col-md-2 col-sm-2 form-group variant-container"
     ng-show="txCtrl.newItem.variants.variant1_active == true">
    <label class="col-lg-3 control-label">@{{ txCtrl.newItem.variants.variant1_name }}</label>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-float">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="@{{ txCtrl.newItem.variants.variant1_name }}"
                   ng-model="txCtrl.newItem.variant1_value">
            <div class="input-group-btn">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-select-col-2">
                    <li ng-repeat="variant in txCtrl.newItem.variants.variant1_variants">
                        <a ng-click="txCtrl.newItem.variant1_value = variant.value">@{{ variant.value }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-2 col-md-2 col-sm-2 form-group variant-container"
     ng-show="txCtrl.newItem.variants.variant2_active == true">
    <label class="col-lg-3 control-label">@{{ txCtrl.newItem.variants.variant2_name }}</label>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-float">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="@{{ txCtrl.newItem.variants.variant2_name }}"
                   ng-model="txCtrl.newItem.variant2_value">
            <div class="input-group-btn">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-select-col-2">
                    <li ng-repeat="variant in txCtrl.newItem.variants.variant2_variants">
                        <a ng-click="txCtrl.newItem.variant2_value = variant.value">@{{ variant.value }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-2 col-md-2 col-sm-2 form-group variant-container"
     ng-show="txCtrl.newItem.variants.variant3_active == true">
    <label class="col-lg-3 control-label">@{{ txCtrl.newItem.variants.variant3_name }}</label>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-float">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="@{{ txCtrl.newItem.variants.variant3_name }}"
                   ng-model="txCtrl.newItem.variant3_value">
            <div class="input-group-btn">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-select-col-2">
                    <li ng-repeat="variant in txCtrl.newItem.variants.variant3_variants">
                        <a ng-click="txCtrl.newItem.variant3_value = variant.value">@{{ variant.value }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-2 col-md-2 col-sm-2 form-group variant-container"
     ng-show="txCtrl.newItem.variants.variant4_active == true">
    <label class="col-lg-3 control-label">@{{ txCtrl.newItem.variants.variant4_name }}</label>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-float">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="@{{ txCtrl.newItem.variants.variant4_name }}"
                   ng-model="txCtrl.newItem.variant4_value">
            <div class="input-group-btn">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-select-col-2">
                    <li ng-repeat="variant in txCtrl.newItem.variants.variant4_variants">
                        <a ng-click="txCtrl.newItem.variant4_value = variant.value">@{{ variant.value }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>