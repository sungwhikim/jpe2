<!-- app/views/page/country.blade.php -->

@extends('main')

@section('nav')
    @include('layouts.nav')
@stop

@section('body')
    <div class="container main-content" ng-controller="ListController as mainList" st-table="mainList.displayItems" st-safe-src="mainList.items">
        <div class="row content-header">
            <div class="col-lg-7 col-md-6 col-sm-5">
                <h1>Clients</h1>
            </div>
            @include('layouts.search-bar', ['criterias' => ['short_name' => 'Short Name',
                                                            'name' => 'Name',
                                                            'warehouse_name' => 'Warehouse',
                                                            'company_name' => 'Company',
                                                            'city' => 'City',
                                                            'province_name' => 'Province/State',
                                                            'active' => 'Active']])
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
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-striped table-hover table-form" ng-cloak>
                    <thead>
                    <tr>
                        <th class="td-button"><button class="btn btn-warning btn-sm" data-toggle="collapse" data-target="#new-item">New</button></th>
                        <th class="sort-header" st-sort="short_name" st-sort-default="true">Short Name</th>
                        <th class="sort-header" st-sort="name">Name</th>
                        <th class="sort-header" st-sort="city">City</th>
                        <th class="sort-header" st-sort="province_name">Province/State</th>
                        <th class="sort-header" st-sort="active">Active</th>
                    </tr>
                    <tr>
                        <td colspan="6" class="td-form">
                            <div class="collapse" id="new-item">
                                <form class="form-horizontal" id="formNew" name="formNew" ng-submit="formNew.$valid && mainList.add(formNew)" novalidate>
                                    <fieldset>
                                        @include('forms.new-short-name', ['size' => 30])
                                        @include('forms.new-name', ['size' => 100])
                                        @include('forms.new-address1')
                                        @include('forms.new-address2')
                                        @include('forms.new-city')
                                        @include('forms.new-postal-code')
                                        @include('forms.new-province-state')
                                        @include('forms.new-company')
                                        @include('forms.new-product-type', ['default' => 'Default'])
                                        @include('forms.new-filo-lifo')
                                        @include('forms.new-toggle-button', ['title' => 'Show client barcode in receiving screen',
                                                                             'name' => 'show_barcode_client',
                                                                             'required' => false,
                                                                             'default' => 'false'])
                                        @include('forms.new-toggle-button', ['title' => 'Attach CSV to transaction confirmation emails',
                                                                             'name' => 'tx_email_csv',
                                                                             'required' => false,
                                                                             'default' => 'false'])
                                        @include('forms.divider', ['style' => 'margin-top:30px;'])
                                        @include('forms.new-checkbox-list', ['title' => 'Warehouses',
                                                                             'name' => 'warehouses',
                                                                             'container_class' => 'checkbox-list-left',
                                                                             'list_data' => $warehouse_data,
                                                                             'master_list' => 'mainList.warehouseData'])
                                        @include('forms.new-contact')
                                        @include('forms.new-email')
                                        @include('forms.new-phone')
                                        @include('forms.new-fax')

                                        @include('forms.divider', ['title' => 'Billing'])
                                        @include('forms.new-text', ['title' => 'Contact',
                                                                    'name' => 'billing_contact',
                                                                    'size' => 100,
                                                                    'required' => false])
                                        @include('forms.new-billing-email')
                                        @include('forms.new-text', ['title' => 'Terms',
                                                                    'name' => 'terms',
                                                                    'size' => 100,
                                                                    'required' => false])
                                        @include('forms.new-currency')
                                        @include('forms.new-taxable')
                                        @include('forms.new-invoice-attachment-type')

                                        @include('forms.new-edit-buttons', ['active_flag' => true])
                                    </fieldset>
                                </form>
                            </div>
                        </td>
                    </tr>
                    </thead>
                    <tbody dir-paginate="item in mainList.displayItems | itemsPerPage: 10" class="tbody-form">
                    <tr>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#item-@{{ item.id }}">Edit</button>
                            </div>
                        </td>
                        <td ng-bind="item.short_name"></td>
                        <td ng-bind="item.name"></td>
                        <td ng-bind="item.city"></td>
                        <td ng-bind="item.province_name"></td>
                        <td><span ng-bind="item.active" ng-class="{'badge': item.active===true}"></span></td>
                    </tr>
                    <tr>
                        <td colspan="6" class="td-form">
                            <div class="collapse col-lg-12" id="item-@{{ item.id }}">
                                <form class="form-horizontal" name="dataForm" ng-submit="dataForm.$valid && mainList.save(item)" novalidate>
                                    <input class="item-id" type="hidden" ng-model="item.id" name="id">
                                    <fieldset>
                                        @include('forms.short-name', ['size' => 30])
                                        @include('forms.name', ['size' => 100])
                                        @include('forms.address1')
                                        @include('forms.address2')
                                        @include('forms.city')
                                        @include('forms.postal-code')
                                        @include('forms.province-country')
                                        @include('forms.company')
                                        @include('forms.product-type', ['default' => 'Default'])
                                        @include('forms.filo-lifo')
                                        @include('forms.toggle-button', ['title' => 'Show client barcode in receiving screen',
                                                                         'name' => 'show_barcode_client',
                                                                         'required' => false,
                                                                         'default' => 'false'])
                                        @include('forms.toggle-button', ['title' => 'Attach CSV to transaction confirmation emails',
                                                                         'name' => 'tx_email_csv',
                                                                         'required' => false,
                                                                         'default' => 'false'])

                                        @include('forms.divider', ['style' => 'margin-top:30px;'])
                                        @include('forms.checkbox-list', ['title' => 'Warehouses',
                                                                         'name' => 'warehouses',
                                                                         'container_class' => 'checkbox-list-left',
                                                                         'list_data' => $warehouse_data,
                                                                         'master_list' => 'mainList.warehouseData'])
                                        @include('forms.contact')
                                        @include('forms.email')
                                        @include('forms.phone')
                                        @include('forms.fax')

                                        @include('forms.divider', ['title' => 'Billing'])
                                        @include('forms.text', ['title' => 'Contact',
                                                                'name' => 'billing_contact',
                                                                'size' => 100,
                                                                'required' => false])
                                        @include('forms.billing-email')
                                        @include('forms.text', ['title' => 'Terms',
                                                                'name' => ' terms',
                                                                'size' => 100,
                                                                'required' => false])
                                        @include('forms.currency')
                                        @include('forms.taxable')
                                        @include('forms.invoice-attachment-type')

                                        @include('forms.edit-buttons', ['active_flag' => true])
                                    </fieldset>
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
         var countryData = {!! $country_data !!};
         var companyData = {!! $company_data !!};
         var warehouseData = {!! $warehouse_data !!};
         var productTypeData = {!! $product_type_data !!};
     </script>
@stop

@section('js-footer')
    @include('layouts.angular-js')
    @include('layouts.list-js')
@stop