<div class="row col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
    <label class="col-lg-3 control-label">Product</label>
    <div class="col-lg-12 col-md-12 no-float">
        <div class="input-group">
            <input type="text" class="form-control" id="search-select-product" ng-value="txCtrl.SearchSelectProduct.selectedItem.sku"
                   ng-model="txCtrl.SearchSelectProduct.searchTerm" ng-keyup="txCtrl.SearchSelectProduct.search()" placeholder="-- select a product --"
                   onkeyup="document.getElementById('search-select-container').className += ' open';" ng-focus="txCtrl.SearchSelectProduct.search();">
            <div class="input-group-btn search-select-container" id="search-select-container">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu search-select-dropdown">
                    <li ng-show="txCtrl.SearchSelectProduct.displayItems.length == 0"
                        style="padding:5px 20px; color:grey;">no products found</li>
                    <li ng-repeat="product in txCtrl.SearchSelectProduct.displayItems">
                        <a ng-click="txCtrl.SearchSelectProduct.selectItem(product)"><strong>@{{ product.sku }} - </strong>@{{ product.name }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>


