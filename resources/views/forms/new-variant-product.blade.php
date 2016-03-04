<div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group">
    <label class="col-lg-3 control-label text-right">Variants</label>
    <div class="col-lg-8">
        <ul class="tags">
            <li class="tag-default" ng-show="mainList.newItem.product_type.variant1_active == true">
                <span>@{{ mainList.newItem.product_type.variant1 }}</span>
            </li>
            <li class="tag-default" ng-show="mainList.newItem.product_type.variant2_active == true">
                <span>@{{ mainList.newItem.product_type.variant2 }}</span>
            </li>
            <li class="tag-default" ng-show="mainList.newItem.product_type.variant3_active == true">
                <span>@{{ mainList.newItem.product_type.variant3 }}</span>
            </li>
            <li class="tag-default" ng-show="mainList.newItem.product_type.variant4_active == true">
                <span>@{{ mainList.newItem.product_type.variant4 }}</span>
            </li>
        </ul>
    </div>
</div>