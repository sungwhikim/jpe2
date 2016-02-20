<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {{ $container_class }}">
    <div class="panel panel-default panel-checkbox">
        <div class="panel-heading">
            <label style="margin-left: 15px;">{{ $title }}</label>&nbsp;&nbsp;
            <button class="btn btn-xs btn-default" ng-click="mainList.allCheckBoxes(mainList.newItem.{{ $name }})" type="button">All</button>
            <button class="btn btn-xs btn-default" ng-click="mainList.noneCheckBoxes(mainList.newItem.{{ $name }})" type="button">None</button>
        </div>
        <div class="panel-body checkbox-list-body" style="height: {{ $height or '250px' }}">
            <ul class="nav nav-pills nav-stacked">
                <li class="checkbox" ng-repeat="(key, value) in mainList.checkboxItems">
                    <label>
                        <input type="checkbox" name="{{ $name }}[]" value=""
                               ng-click="mainList.toggleCheckBox(mainList.newItem.{{ $name }}, @{{ value.id }})"
                               ng-checked="mainList.newItem.{{ $name }}.indexOf(@{{ value.id }}) > -1">
                        @{{ value.name }}
                    </label>
                </li>
            </ul>
        </div>
    </div>
</div>