<div class="col-lg-5 col-md-6 col-sm-7 col-xs-10" ng-cloak>
    <span class="item-total-container"><span id="item-total" class="badge" ng-bind="mainList.displayItems.length"></span>  items</span>
    <form class="">
        <div class="form-group">
            <label class="control-label"></label>
            <div class="input-group">
                        <span class="search-criteria">
                            <select class="form-control" ng-model="mainList.searchCriteria">
                                <option value="">All</option>
                                @foreach( $criterias as $criteria => $label )
                                    <option value="{{ $criteria }}">{{ ucfirst($label) }}</option>
                                @endforeach
                            </select>
                        </span>
                <input placeholder="Search" st-search="@{{ mainList.searchCriteria }}" class="form-control" type="search"/>
                        <span class="input-group-btn">
                          <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button>
                        </span>
            </div>
        </div>
    </form>
</div>