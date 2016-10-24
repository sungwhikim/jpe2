<div class="pull-left report-control" style="width: 235px; margin: 0 15px 20px 0;">
    <label class="col-lg-3 control-label">Product</label>
    <div class="col-lg-12 col-md-12 no-float" style="padding-right: 0;">
        <div class="input-group">
            <input type="text" class="form-control" id="search-select-product" ng-value="ctrl.SearchSelectProduct.selectedItem.sku"
                   ng-model="ctrl.SearchSelectProduct.searchTerm" ng-change="ctrl.searchProduct()" placeholder="-- All Products --" autocomplete="off"
                   onkeyup="document.getElementById('search-select-container').className += ' open';" ng-focus="ctrl.clearProductSearch();">
            <div class="input-group-btn search-select-container" id="search-select-container">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" style="height: 34px;"
                        ng-click="ctrl.SearchSelectProduct.loadDefaultList();">
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu search-select-dropdown" style="left: -187px; max-width: 475px;">
                    <li ng-show="ctrl.SearchSelectProduct.displayItems.length == 0"
                        style="padding:5px 20px; color:grey;">no products found</li>
                    <li ng-repeat="product in ctrl.SearchSelectProduct.displayItems">
                        <a ng-click="ctrl.SearchSelectProduct.selectItem(product)"><strong>@{{ product.sku }} - </strong>@{{ product.name }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>


