<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {{ $container_class }}">
    <div class="panel panel-default panel-checkbox">
        <div class="panel-heading">
            <label style="margin-left: 15px;">Clients</label>&nbsp;&nbsp;
            <button class="btn btn-xs btn-default" ng-click="mainList.allCheckBoxes(mainList.newItem.clients, mainList.clientData)" type="button">All</button>
            <button class="btn btn-xs btn-default" ng-click="mainList.noneCheckBoxes(mainList.newItem.clients)" type="button">None</button>
        </div>
        <div class="panel-body checkbox-list-body" style="height: {{ $height or '250px' }}">
            <ul class="nav nav-pills nav-stacked">
                @foreach( $client_data as $item)
                <li class="checkbox">
                    <label>
                        <input type="checkbox" name="clients[]" value=""
                               ng-click="mainList.toggleCheckBox(item.clients, {{ $item->id }})"
                               ng-checked="mainList.newItem.clients.indexOf({{ $item->id }}) > -1">
                        <strong>{{ $item->short_name }}</strong> - {{ $item->name }}
                    </label>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>