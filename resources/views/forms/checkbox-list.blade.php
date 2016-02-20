<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {{ $container_class }}">
    <div class="panel panel-default panel-checkbox">
        <div class="panel-heading">
            <label style="margin-left: 15px;">{{ $title }}</label>&nbsp;&nbsp;
            <button class="btn btn-xs btn-default" ng-click="mainList.allCheckBoxes(item.{{ $name }}, {{ $master_list }})" type="button">All</button>
            <button class="btn btn-xs btn-default" ng-click="mainList.noneCheckBoxes(item.{{ $name }})" type="button">None</button>
        </div>
        <div class="panel-body checkbox-list-body" style="height: {{ $height or '250px' }}">
            <ul class="nav nav-pills nav-stacked">
                @foreach( $list_data as $item)
                <li class="checkbox">
                    <label>
                        <input type="checkbox" name="{{ $name }}[]" value=""
                               ng-click="mainList.toggleCheckBox(item.{{ $name }}, {{ $item->id }})"
                               ng-checked="item.{{ $name }}.indexOf({{ $item->id }}) > -1">
                        {{ $item->name }}
                    </label>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>