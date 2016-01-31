<div class="row col-lg-12 col-md-12 col-sm-12">
    <div class="row col-lg-6 col-md-6 col-sm-7 col-xs-12 form-group button-group">
        <div class="col-lg-3 control-label text-right"></div>
        <div class="col-lg-5 col-md-8 col-sm-7 col-xs-6 text-left">
            <button class="btn btn-default" ng-click="mainList.resetData()">Cancel</button>
            <button type="submit" class="btn btn-success">Save</button>
            <a ng-click="mainList.deleteConfirm($index)"
               class="btn glyphicon glyphicon-trash btn-delete"
               data-toggle="tooltip" title="Delete"></a>
        </div>
        @if( isset($active_flag) )
        <div class="col-lg-3 col-md-4 col-sm-5 col-xs-3">
            <div class="checkbox checkbox-slider--b checkbox-slider-md">
                <label>
                    <input type="checkbox" name="active" value="true" ng-model="item.active"><span>Active</span>
                </label>
            </div>
        </div>
        @endif
    </div>
</div>