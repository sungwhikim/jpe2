<div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <label style="margin-left: 15px;">User Functions</label>
    </div>
    @foreach( $user_functions as $user_function )
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                {{ $user_function['category_name'] }}&nbsp;&nbsp;
                {{--<button class="btn btn-xs btn-default" onclick="allCheckBoxes(this)" type="button">All</button>--}}
                {{--<button class="btn btn-xs btn-default" onclick="noneCheckBoxes(this)" type="button">None</button>--}}
            </div>
            <div class="panel-body checkbox-list">
                <ul class="nav nav-pills nav-stacked">
                @foreach( $user_function['functions'] as $function)
                    @if( strtolower($function['name']) == 'divider' || strtolower($function['url']) == 'divider' )
                    <li class="divider"></li>
                    @else
                    <li class="checkbox">
                        <label>
                            <input type="checkbox" name="user_function_id[]" value="{{ $function['id'] }}"
                                   ng-click="mainList.toggleCheckBox(item.user_function_id, {{ $function['id'] }}, item)"
                                   ng-checked="item.user_function_id.indexOf({{ $function['id'] }}) > -1">
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