<div class="row col-lg-12 col-md-12 col-sm-12">
    <div class="row col-lg-6 col-md-6 col-sm-6 col-xs-12 form-group button-group">
        <div class="col-lg-3 control-label text-right"></div>
        <div class="col-lg-5 col-md-8 col-sm-7 col-xs-6 text-left">
            <button class="btn btn-default" type="reset" data-toggle="collapse" data-target="#new-item">Cancel</button>
            <button type="submit" class="btn btn-success">Add</button>
        </div>
        @if( isset($active_flag) )
        <div class="col-lg-3 col-md-4 col-sm-5 col-xs-4">
            <div class="checkbox checkbox-slider--b checkbox-slider-md">
                <label>
                    <input type="checkbox" name="active" value="true" ng-model="mainList.newItem.active"><span>Active</span>
                </label>
            </div>
        </div>
        @endif
    </div>
</div>