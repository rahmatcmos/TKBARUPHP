@extends('layouts.adminlte.master')

@section('title')
    @lang('warehouse.edit.title')
@endsection

@section('page_title')
    <span class="fa fa-wrench fa-fw"></span>&nbsp;@lang('warehouse.edit.page_title')
@endsection

@section('page_title_desc')
    @lang('warehouse.edit.page_title_desc')
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('master_warehouse_edit', $warehouse->hId()) !!}
@endsection

@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>@lang('labels.GENERAL_ERROR_TITLE')</strong> @lang('labels.GENERAL_ERROR_DESC')<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">@lang('warehouse.edit.header.title')</h3>
        </div>
        {!! Form::model($warehouse, ['method' => 'PATCH', 'route' => ['db.master.warehouse.edit', $warehouse->hId()], 'class' => 'form-horizontal', 'data-parsley-validate' => 'parsley']) !!}
            <div id="warehouseVue">
                <div class="box-body">
                    <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label">@lang('warehouse.field.name')</label>
                        <div class="col-sm-10">
                            <input id="inputName" name="name" type="text" class="form-control" value="{{ $warehouse->name }}" placeholder="Name" data-parsley-required="true">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputAddress" class="col-sm-2 control-label">@lang('warehouse.field.address')</label>
                        <div class="col-sm-10">
                            <input id="inputAddress" name="address" type="text" class="form-control" value="{{ $warehouse->address }}" placeholder="Address">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPhoneNum" class="col-sm-2 control-label">@lang('warehouse.field.phone_num')</label>
                        <div class="col-sm-10">
                            <input id="inputPhoneNum" name="phone_num" type="text" class="form-control" value="{{ $warehouse->phone_num}}" placeholder="Phone Number">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputSection" class="col-sm-2 control-label">@lang('warehouse.field.section')</label>
                        <div class="col-sm-10">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>@lang('warehouse.edit.table.header.name')</th>
                                        <th>@lang('warehouse.edit.table.header.position')</th>
                                        <th>@lang('warehouse.edit.table.header.capacity')</th>
                                        <th>@lang('warehouse.edit.table.header.capacity_unit')</th>
                                        <th>@lang('warehouse.edit.table.header.remarks')</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="c in sections">
                                        <td><input type="text" class="form-control" v-model="c.name" name="section_name[]" data-parsley-required="true"/></td>
                                        <td><input type="text" class="form-control" v-model="c.position" name="section_position[]" data-parsley-required="true"/></td>
                                        <td><input type="text" class="form-control" v-model="c.capacity" name="section_capacity[]" data-parsley-required="true" data-parsley-type="number"/></td>
                                        <td>
                                            <select class="form-control"
                                                    name="section_capacity_unit[]"
                                                    v-model="c.capacity_unit_id"
                                                    data-parsley-required="true">
                                                <option value="">@lang('labels.PLEASE_SELECT')</option>
                                                <option v-for="u in unitDDL" v-bind:value="u.id">@{{ u.name }} (@{{ u.symbol }})</option>
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control" v-model="c.remarks" name="section_remarks[]"/></td>
                                        <td class="text-center valign-middle">
                                            <button type="button" class="btn btn-xs btn-danger" data="@{{ $index }}" v-on:click="removeSelected($index)">
                                                <span class="fa fa-close fa-fw"></span>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6">
                                            <button type="button" class="btn btn-xs btn-default" v-on:click="addNew()">@lang('buttons.create_new_button')</button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                        <label for="inputStatus" class="col-sm-2 control-label">@lang('bank.field.status')</label>
                        <div class="col-sm-10">
                            {{ Form::select('status', $statusDDL, null, array('class' => 'form-control', 'placeholder' => Lang::get('labels.PLEASE_SELECT'), 'data-parsley-required' => 'true')) }}
                            <span class="help-block">{{ $errors->has('status') ? $errors->first('status') : '' }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputRemarks" class="col-sm-2 control-label">@lang('bank.field.remarks')</label>
                        <div class="col-sm-10">
                            <input id="inputRemarks" name="remarks" type="text" class="form-control" value="{{ $warehouse->remarks }}" placeholder="Remarks">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputButton" class="col-sm-2 control-label"></label>
                        <div class="col-sm-10">
                            <a href="{{ route('db.master.warehouse') }}" class="btn btn-default">@lang('buttons.cancel_button')</a>
                            <button class="btn btn-default" type="submit">@lang('buttons.submit_button')</button>
                        </div>
                    </div>
                </div>
                <div class="box-footer"></div>
            </div>
        {!! Form::close() !!}
    </div>
@endsection

@section('custom_js')
    <script type="application/javascript">
        var app = new Vue({
            el: '#warehouseVue',
            data: {
                sections: JSON.parse('{!! htmlspecialchars_decode($warehouse->sections) !!}'),
                unitDDL: JSON.parse('{!! htmlspecialchars_decode($unitDDL) !!}')
            },
            methods: {
                addNew: function () {
                    this.sections.push({
                        'name': '',
                        'position': '',
                        'capacity': 0,
                        'remarks': ''
                    });
                },
                removeSelected: function (idx) {
                    this.sections.splice(idx, 1);
                }
            }
        });
    </script>
@endsection