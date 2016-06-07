<div class="col-lg-9 col-md-12 col-sm-12" st-table="mainList.displayProducts" st-safe-src="mainList.products" ng-cloak>
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="btn-group product-dropdown input-group">
            <div class="input-group-btn">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-left" ng-show="mainList.uoms.length > 0">
                    <li ng-repeat="uom in mainList.uoms"
                        ng-class="{active: uom.key == mainList.selectedUom}">
                        <a ng-click="mainList.selectUom(uom)">@{{ uom.name }}</a>
                    </li>
                </ul>
            </div>
            <input type="text" class="form-control product-label dropdown-toggle" data-toggle="dropdown"
                   value="@{{ mainList.selectedProduct.sku }} - @{{ mainList.selectedProduct.name }}"
               ng-init="mainList.selectedProduct.sku = '- select a product'" readonly>
            <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                &nbsp;<span id="item-total" class="badge" ng-bind="mainList.displayProducts.length"></span>&nbsp;
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li ng-repeat="product in mainList.displayProducts">
                    <a href="#" ng-click="mainList.selectProduct(product)"><strong>@{{ product.sku }}</strong> - @{{ product.name }}</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-lg-5 col-md-5 col-sm-6 col-xs-7">
        <form class="">
            <div class="form-group">
                <label class="control-label"></label>
                <div class="input-group">
                    <span class="search-criteria">
                        <select class="form-control" ng-model="mainList.productSearch">
                            <option value="">All</option>
                            <option value="sku">SKU</option>
                            <option value="sku_client">Client SKU</option>
                            <option value="name">Name</option>
                            <option value="barcode">JPE Barcode</option>
                            <option value="barcode_client">Client Barcode</option>
                            <option value="rfid">RFID</option>
                            <option value="active">Active</option>
                        </select>
                    </span>
                    <input placeholder="Search Product" st-search="@{{ productList.productSearch }}" class="form-control" type="search"/>
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button>
                    </span>
                </div>
            </div>
        </form>
    </div>
</div>