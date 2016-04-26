<div class="row col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
    <label class="col-lg-3 control-label">Product</label>
    <div class="col-lg-12 no-float">
        <input type="text" ng-model="txCtrl.newItem.product_id" uib-typeahead="product.id as product.sku for product in txCtrl.products | filter:$viewValue" class="form-control">
    </div>
</div>


