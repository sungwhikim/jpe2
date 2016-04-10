<div class="row col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
    <label class="col-lg-3 control-label">Product</label>
    <div class="col-lg-12 no-float">
        <select class="form-control" name="product" ng-model="txCtrl.newItem.product_id"
                ng-change="txCtrl.selectProduct(txCtrl.newItem.product_id)"
                ng-options="product.id as product.sku for product in txCtrl.products" required>
            <option value="">-- select a product --</option>
        </select>
    </div>
</div>


