<div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <label style="margin: 0 10px 10px 15px;">User Functions</label>
        <button class="btn btn-xs btn-default" ng-click="mainList.allCheckBoxes(mainList.newItem.user_function_id, mainList.userFunctionList)" type="button">All</button>
        <button class="btn btn-xs btn-default" ng-click="mainList.noneCheckBoxes(mainList.newItem.user_function_id)" type="button">None</button>
    </div>
    @foreach( $user_functions as $user_function )
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                {{ $user_function['category_name'] }}
            </div>
            <div class="panel-body checkbox-list-body">
                <ul class="nav nav-pills nav-stacked">
                @foreach( $user_function['functions'] as $function)
                    @if( strtolower($function['name']) == 'divider' || strtolower($function['url']) == 'divider' )
                    <li>
                        <input type="checkbox" name="user_function_id[]" value="{{ $function['id'] }}"
                               ng-click="mainList.toggleCheckBox(mainList.newItem.user_function_id, {{ $function['id'] }}, item)"
                               ng-checked="mainList.newItem.user_function_id.indexOf({{ $function['id'] }}) > -1">
                        <div class="divider-checkbox"></div>
                    </li>
                    @else
                    <li class="checkbox">
                        <label>
                            <input type="checkbox" name="user_function_id[]" value="{{ $function['id'] }}"
                                   ng-click="mainList.toggleCheckBox(mainList.newItem.user_function_id, {{ $function['id'] }}, item)"
                                   ng-checked="mainList.newItem.user_function_id.indexOf({{ $function['id'] }}) > -1">
                            {{ $function['name'] }}
                        </label>
                    </li>
                    @endif
                @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endforeach
</div>