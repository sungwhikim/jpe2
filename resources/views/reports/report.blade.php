@extends('main')

@section('nav')
    @include('layouts.nav')
@stop

@section('body')
    <div class="container main-content" ng-controller="ReportController as ctrl">
        <div class="row content-header">
            <div class="col-lg-12">
                <h1>{{ $report['title'] }}</h1>
            </div>
        </div>

        <div class="status-row">
            <div class="col-lg-12">
                <div class="alert alert-dismissible alert-@{{ item.type }}" ng-repeat="item in ctrl.alerts" ng-cloak>
                    <span class="glyphicon glyphicon-@{{ item.type }}"></span>&nbsp;
                    <button type="button" class="close" data-dismiss="item" ng-click="ctrl.closeAlert($index)">x</button>
                    <span ng-bind="item.msg"></span>
                </div>
            </div>
        </div>

        <div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12 report-criteria-container">
            <form class="form-horizontal" id="reportForm" name="reportForm" novalidate>
                @yield('report-criteria')
                <div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12 report-action-buttons">
                    <button type="button" class="btn btn-success" ng-click="ctrl.submit(reportForm, 'view')">View</button>
                    <button type="button" class="btn btn-info" ng-click="ctrl.submit(reportForm, 'print')" style="margin-left: 30px;">Print</button>
                    <button type="button" class="btn btn-warning" ng-click="ctrl.submit(reportForm, 'excel')">Excel</button>
                </div>
            </form>
        </div>

        <div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12 report-data-container">
            @yield('report-data')
        </div>

    </div>
@stop

@yield('js-data')

@section('js-footer')
    @include('layouts.angular-js')
    @include('layouts.report-js')
@stop