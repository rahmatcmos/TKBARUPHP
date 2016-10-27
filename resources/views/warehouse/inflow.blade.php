@extends('layouts.adminlte.master')

@section('title')
    @lang('warehouse.inflow.index.title')
@endsection

@section('page_title')
    <span class="fa fa-mail-forward fa-rotate-90 fa-fw"></span>&nbsp;@lang('warehouse.inflow.index.page_title')
@endsection
@section('page_title_desc')
    @lang('warehouse.inflow.index.page_title_desc')
@endsection

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <div ng-app="warehouseModule" ng-controller="warehouseController">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('warehouse.inflow.index.header.warehouse')</h3>
            </div>
            <div class="box-body">
                <select id="inputWarehouse"
                        class="form-control"
                        ng-model="warehouse"
                        ng-options="warehouse as warehouse.name for warehouse in warehouseDDL track by warehouse.id">
                    <option value="">@lang('labels.PLEASE_SELECT')</option>
                </select>
            </div>
        </div>
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('warehouse.inflow.index.header.purchase_order')</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th class="text-center">@lang('warehouse.inflow.index.table.header.code')</th>
                        <th class="text-center">@lang('warehouse.inflow.index.table.header.po_date')</th>
                        <th class="text-center">@lang('warehouse.inflow.index.table.header.supplier')</th>
                        <th class="text-center">@lang('warehouse.inflow.index.table.header.shipping_date')</th>
                        <th class="text-center">@lang('labels.ACTION')</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="po in selectedWarehousePo(warehouse)">
                            <td class="text-center">@{{ po.code }}</td>
                            <td class="text-center">@{{ po.po_created }}</td>
                            <td class="text-center">@{{ po.supplier.name }}</td>
                            <td class="text-center">@{{ po.shipping_date }}</td>
                            <td class="text-center" width="20%">
                                <a class="btn btn-xs btn-primary" href="{{ route('db.warehouse.inflow') }}/@{{ po.id }}" title="Receipt"><span class="fa fa-pencil fa-fw"></span></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('custom_js')
    <script type="application/javascript">
        var app = angular.module('warehouseModule', []);
        app.controller('warehouseController', ['$scope', function($scope) {
            $scope.warehouseDDL = JSON.parse('{!! htmlspecialchars_decode($warehouseDDL) !!}');
            $scope.allPOs = JSON.parse('{!! htmlspecialchars_decode($allPOs) !!}');

            $scope.selectedWarehousePo = function (warehouse) {
                return _.filter($scope.allPOs, function (PO) {
                    return PO.warehouse_id === warehouse.id;
                });
            };
        }]);
    </script>
@endsection