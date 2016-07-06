<div class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" ng-click="cancel()" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2 class="modal-title">New Customer</h2>
            </div>
            <div class="modal-body bin-container td-form customer-form">
                <div class="status-row">
                    <div class="col-lg-12">
                        <div class="alert alert-dismissible alert-@{{ item.type }}" ng-repeat="item in alerts" ng-cloak>
                            <span class="glyphicon glyphicon-@{{ item.type }}"></span>&nbsp;
                            <button type="button" class="close" data-dismiss="item" ng-click="closeAlert($index)">x</button>
                            <span ng-bind="item.msg"></span>
                        </div>
                    </div>
                </div>
                <div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <form class="form-horizontal" id="formNew" name="formNew" ng-submit="formNew.$valid && add()" novalidate>
                        <fieldset>
                            <div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
                                 ng-class="{ 'has-error': (formNew.name.$touched || formNew.$submitted) && formNew.name.$invalid }">
                                <label class="col-lg-3 control-label text-right">Name <span class="required-field glyphicon glyphicon-asterisk" /></label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="name" placeholder="Name"
                                           ng-model="newItem.name" ng-maxlength="50"
                                           ng-model-options="{ updateOn: 'blur' }" required>
                                    <!-- ngMessages goes here -->
                                    <div class="help-block ng-messages" ng-messages="formNew.name.$error"
                                         ng-if="formNew.$submitted || formNew.name.$touched">
                                        @include('layouts.validate-message')
                                    </div>
                                </div>
                            </div>
                            <div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
                                 ng-class="{ 'has-error': (formNew.address1.$touched || formNew.$submitted) && formNew.address1.$invalid }">
                                <label class="col-lg-3 control-label text-right">Address 1 <span class="required-field glyphicon glyphicon-asterisk" /></label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="address1" placeholder="Address 1"
                                           ng-model="newItem.address1" ng-maxlength="100"
                                           ng-model-options="{ updateOn: 'blur' }" required>
                                    <!-- ngMessages goes here -->
                                    <div class="help-block ng-messages" ng-messages="formNew.address1.$error"
                                         ng-if="formNew.$submitted || formNew.address1.$touched">
                                        @include('layouts.validate-message')
                                    </div>
                                </div>
                            </div>
                            <div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
                                 ng-class="{ 'has-error': (formNew.address2.$touched || formNew.$submitted) && formNew.address2.$invalid }">
                                <label class="col-lg-3 control-label text-right">Address 2</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="address2" placeholder="Address 2"
                                           ng-model="newItem.address2" ng-maxlength="100"
                                           ng-model-options="{ updateOn: 'blur' }">
                                    <!-- ngMessages goes here -->
                                    <div class="help-block ng-messages" ng-messages="formNew.address2.$error"
                                         ng-if="formNew.$submitted || formNew.address2.$touched">
                                        @include('layouts.validate-message')
                                    </div>
                                </div>
                            </div>
                            <div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
                                 ng-class="{ 'has-error': (formNew.city.$touched || formNew.$submitted) && formNew.city.$invalid }">
                                <label class="col-lg-3 control-label text-right">City <span class="required-field glyphicon glyphicon-asterisk" /></label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="city" placeholder="City"
                                           ng-model="newItem.city" ng-maxlength="50"
                                           ng-model-options="{ updateOn: 'blur' }" required>
                                    <!-- ngMessages goes here -->
                                    <div class="help-block ng-messages" ng-messages="formNew.city.$error"
                                         ng-if="formNew.$submitted || formNew.city.$touched">
                                        @include('layouts.validate-message')
                                    </div>
                                </div>
                            </div>
                            <div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
                                 ng-class="{ 'has-error': (formNew.postal_code.$touched || formNew.$submitted) && formNew.postal_code.$invalid }">
                                <label class="col-lg-3 control-label text-right">Postal Code <span class="required-field glyphicon glyphicon-asterisk" /></label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="postal_code" placeholder="Postal Code"
                                           ng-model="newItem.postal_code" ng-maxlength="30"
                                           ng-model-options="{ updateOn: 'blur' }" required>
                                    <!-- ngMessages goes here -->
                                    <div class="help-block ng-messages" ng-messages="formNew.postal_code.$error"
                                         ng-if="formNew.$submitted || formNew.postal_code.$touched">
                                        @include('layouts.validate-message')
                                    </div>
                                </div>
                            </div>
                            <div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
                                 ng-class="{ 'has-error': (formNew.country_id.$touched || formNew.$submitted) && formNew.country_id.$invalid }">
                                <label class="col-lg-3 control-label text-right">Country <span class="required-field glyphicon glyphicon-asterisk" /></label>
                                <div class="col-lg-8">
                                    <select class="form-control" name="country_id" ng-model="newItem.country_id"
                                            ng-change="formNew.province_id=null" ng-options="country.id as country.name for country in countries" required>
                                        <option value="">-- select a country --</option>
                                    </select>
                                    <!-- ngMessages goes here -->
                                    <div class="help-block ng-messages" ng-messages="formNew.country_id.$error" ng-if="formNew.$submitted || formNew.country_id.$touched">
                                        @include('layouts.validate-message')
                                    </div>
                                </div>
                            </div>
                            <div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
                                 ng-class="{ 'has-error': (formNew.province_id.$touched || formNew.$submitted) && formNew.province_id.$invalid }">
                                <label class="col-lg-3 control-label text-right">Province/State <span class="required-field glyphicon glyphicon-asterisk" /></label>
                                <div class="col-lg-8">
                                    <select class="form-control" name="province_id" ng-model="newItem.province_id" required
                                            ng-options="province.id as province.name for province in (countries | filter:{id:newItem.country_id})[0].provinces">
                                        <option value="">-- select a province/state --</option>
                                    </select>
                                    <!-- ngMessages goes here -->
                                    <div class="help-block ng-messages" ng-messages="formNew.province_id.$error"
                                         ng-if="formNew.$submitted || formNew.province_id.$touched">
                                        @include('layouts.validate-message')
                                    </div>
                                </div>
                            </div>
                            <div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
                                 ng-class="{ 'has-error': (formNew.contact.$touched || formNew.$submitted) && formNew.contact.$invalid }">
                                <label class="col-lg-3 control-label text-right">Contact</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="contact" placeholder="Contact"
                                           ng-model="newItem.contact" ng-maxlength="100"
                                           ng-model-options="{ updateOn: 'blur' }">
                                    <!-- ngMessages goes here -->
                                    <div class="help-block ng-messages" ng-messages="formNew.contact.$error"
                                         ng-if="formNew.$submitted || formNew.contact.$touched">
                                        @include('layouts.validate-message')
                                    </div>
                                </div>
                            </div>
                            <div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
                                 ng-class="{ 'has-error': (formNew.email.$touched || formNew.$submitted) && formNew.email.$invalid }">
                                <label class="col-lg-3 control-label text-right">Email @if( !empty($required) ) <span class="required-field glyphicon glyphicon-asterisk" />@endif</label>
                                <div class="col-lg-8">
                                    <input type="email" class="form-control" name="email" placeholder="Email"
                                           ng-model="newItem.email" ng-maxlength="100"
                                           ng-model-options="{ updateOn: 'blur' }" {{ $required or '' }}>
                                    <!-- ngMessages goes here -->
                                    <div class="help-block ng-messages" ng-messages="formNew.email.$error"
                                         ng-if="formNew.$submitted || formNew.email.$touched">
                                        @include('layouts.validate-message')
                                    </div>
                                </div>
                            </div>
                            <div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
                                 ng-class="{ 'has-error': (formNew.phone.$touched || formNew.$submitted) && formNew.phone.$invalid }">
                                <label class="col-lg-3 control-label text-right">Phone</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="phone" placeholder="Phone"
                                           ng-model="newItem.phone" ng-maxlength="30"
                                           ng-model-options="{ updateOn: 'blur' }">
                                    <!-- ngMessages goes here -->
                                    <div class="help-block ng-messages" ng-messages="formNew.phone.$error"
                                         ng-if="formNew.$submitted || formNew.phone.$touched">
                                        @include('layouts.validate-message')
                                    </div>
                                </div>
                            </div>
                            <div class="row col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group"
                                 ng-class="{ 'has-error': (formNew.fax.$touched || formNew.$submitted) && formNew.fax.$invalid }">
                                <label class="col-lg-3 control-label text-right">Fax</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="fax" placeholder="Fax"
                                           ng-model="newItem.fax" ng-maxlength="30"
                                           ng-model-options="{ updateOn: 'blur' }">
                                    <!-- ngMessages goes here -->
                                    <div class="help-block ng-messages" ng-messages="formNew.fax.$error"
                                         ng-if="formNew.$submitted || formNew.fax.$touched">
                                        @include('layouts.validate-message')
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <div class="row col-lg-11 col-md-11 col-sm-11 col-xs-11 text-right" style="margin-bottom: 15px;">
                            <button type="submit" class="btn btn-success">Save</button>
                            <button type="button" ng-click="cancel()" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
