@extends('main')

@section('nav')
    @include('layouts.nav')
@stop

@section('body')
    <div class="container main-content" ng-controller="InventoryController as mainList">
        <div class="row content-header">
            <div class="col-lg-3 col-md-12 col-sm-12">
                <h1>Inventory</h1>
            </div>
            @include('layouts.product-search')
        </div>
        <div class="status-row">
            <div class="col-lg-12">
                <div class="alert alert-processing text-center" ng-hide="mainList"><strong>Loading Data....</strong></div>
                <div class="alert alert-dismissible alert-@{{ item.type }}" ng-repeat="item in mainList.alerts" ng-cloak>
                    <span class="glyphicon glyphicon-@{{ item.type }}"></span>&nbsp;
                    <button type="button" class="close" data-dismiss="item" ng-click="mainList.closeAlert($index)">x</button>
                    <span ng-bind="item.msg"></span>
                </div>
            </div>
        </div>
        <div class="row col-lg-12 inventory-action-buttons" ng-show="mainList.items.length > 0" ng-cloak>
            <button type="button" class="btn btn-success" ng-click="mainList.saveConfirmInventory()">Update</button>
            <button class="btn btn-warning" data-toggle="collapse" data-target="#new-item">New Bin</button>
            <button class="btn btn-default" ng-click="mainList.resetData(mainList)" type="button">Cancel</button>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-striped table-hover table-form"
                       st-table="mainList.displayItems" st-safe-src="mainList.items"
                       ng-show="mainList.items.length > 0" ng-cloak>
                    <thead>
                    <tr>
                        <th class="button-header"></th>
                        <th class="sort-header">Bin</th>
                        <th class="sort-header">Inventory</th>
                        <th class="sort-header">Active</th>
                    </tr>
                    <tr>
                        <td colspan="4" class="td-form">
                            <div class="collapse" id="new-item">
                                <form class="form-horizontal" id="formNew" name="formNew" ng-submit="formNew.$valid && mainList.addBin(formNew)" novalidate>
                                    <fieldset>
                                        @include('forms.new-bin')

                                        <div class="row col-lg-6 col-md-6 col-sm-6 col-xs-12 form-group button-group">
                                            <div class="col-lg-5 col-md-8 col-sm-7 col-xs-6 text-left">
                                                <button class="btn btn-default" type="reset" data-toggle="collapse" data-target="#new-item">Cancel</button>
                                                <button type="submit" class="btn btn-success">Add</button>
                                            </div>
                                            <div class="col-lg-3 col-md-4 col-sm-5 col-xs-4">
                                                <div class="checkbox checkbox-slider--b checkbox-slider-md">
                                                    <label>
                                                        <input type="checkbox" name="active" value="true" ng-model="mainList.newItem.active"><span>Active</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </form>
                            </div>
                        </td>
                    </tr>
                    </thead>
                    <tbody dir-paginate="item in mainList.displayItems | itemsPerPage: 10" class="tbody-form">
                    <tr>
                        <td>
                            <div class="text-nowrap">
                                <button class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#item-@{{ item.id }}">Edit</button>
                                <a ng-click="mainList.deleteConfirmBin($index, mainList.displayItems)"
                                   class="btn glyphicon glyphicon-trash" style="font-size: 18px;"
                                   data-toggle="tooltip" title="Delete"></a>
                            </div>
                        </td>
                        <td>@{{ item.aisle }} @{{ item.section | numberFixedLen:2 }} @{{ item.tier | numberFixedLen:2 }} @{{ item.position | numberFixedLen:2 }}</td>
                        <td ng-bind="item.total"></td>
                        <td>
                            <div class="checkbox checkbox-slider--b checkbox-slider-md">
                                <label>
                                    <input type="checkbox" name="active" value="true" ng-model="item.active">
                                    <span></span>
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="td-form">
                            <div class="collapse col-lg-11" id="item-@{{ item.id }}">
                                <form class="form-horizontal" name="dataForm" ng-submit="dataForm.$valid && mainList.save(item)" novalidate>
                                    <table class="table table-striped table-hover table-form">
                                        <tr>
                                            <th><button class="btn btn-warning" type="button" ng-click="mainList.newBinItem(item)">New</button></th>
                                            <th ng-show="mainList.selectedProduct.variant1_active == true"
                                                ng-bind="mainList.selectedProduct.variant1"></th>
                                            <th ng-show="mainList.selectedProduct.variant2_active == true"
                                                ng-bind="mainList.selectedProduct.variant2"></th>
                                            <th ng-show="mainList.selectedProduct.variant3_active == true"
                                                ng-bind="mainList.selectedProduct.variant3"></th>
                                            <th ng-show="mainList.selectedProduct.variant4_active == true"
                                                ng-bind="mainList.selectedProduct.variant4"></th>
                                            <th>Receive Date</th>
                                            <th>Inventory</th>
                                            <th>New Quantity</th>
                                        </tr>
                                        <tr class="tr-contains-date-picker" ng-repeat="new_item in item.new_bin_items">
                                            <td></td>
                                            <td ng-show="mainList.selectedProduct.variant1_active == true">
                                                <input class="form-control" type="text" ng-model="new_item.variant1_value">
                                            </td>
                                            <td ng-show="mainList.selectedProduct.variant2_active == true">
                                                <input class="form-control" type="text" ng-model="new_item.variant2_value">
                                            </td>
                                            <td ng-show="mainList.selectedProduct.variant3_active == true">
                                                <input class="form-control" type="text" ng-model="new_item.variant3_value">
                                            </td>
                                            <td ng-show="mainList.selectedProduct.variant4_active == true">
                                                <input class="form-control" type="text" ng-model="new_item.variant4_value">
                                            </td>
                                            <td>
                                                @include('forms.date-picker', ['name' => 'receive_date',
                                                                               'form_name' => 'dataForm',
                                                                               'model_name' => 'new_item.receive_date',
                                                                               'required' => true])
                                            </td>
                                            <td><input class="form-control" type="number" value="0" disabled></td>
                                            <td><input class="form-control" type="number" ng-model="new_item.quantity_new"></td>
                                        </tr>
                                        <tr ng-repeat="bin_item in item.bin_items">
                                            <td></td>
                                            <td ng-show="mainList.selectedProduct.variant1_active == true"
                                                ng-bind="bin_item.variant1_value" ></td>
                                            <td ng-show="mainList.selectedProduct.variant2_active == true"
                                                ng-bind="bin_item.variant2_value" ></td>
                                            <td ng-show="mainList.selectedProduct.variant3_active == true"
                                                ng-bind="bin_item.variant3_value" ></td>
                                            <td ng-show="mainList.selectedProduct.variant4_active == true"
                                                ng-bind="bin_item.variant4_value" ></td>
                                            <td>@{{ bin_item.receive_date | date: "MM-dd-yyyy" }}</td>
                                            <td><input class="form-control" type="number" ng-model="bin_item.quantity" disabled></td>
                                            <td><input class="form-control" type="number" ng-model="bin_item.quantity_new"></td>
                                            {{--<td><button class="btn btn-primary" ng-click="mainList.moveBinItem">Move</button></td>--}}
                                        </tr>
                                    </table>
                                </form>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="text-center pagination-container">
        <dir-pagination-controls boundary-links="true"></dir-pagination-controls>
    </div>

@stop

@section('js-data')
     <script>
         var myName  = '{{ $my_name }}';
         var appUrl  = '{{ $url }}';
         var appData = {!! $main_data !!};
         var productData = {!! $product_data !!};
     </script>
@stop

@section('js-footer')
    @include('layouts.angular-js')
    @include('layouts.inventory-js')
@stop