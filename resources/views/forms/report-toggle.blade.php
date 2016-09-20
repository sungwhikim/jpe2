<div class="report-toggle report-control">
    <label class="control-label">&nbsp;</label>
    <div class="checkbox checkbox-slider--b checkbox-slider-md">
        <label>
            <input type="checkbox" name="{{ $name }}" value="true"
                   ng-model="ctrl.criteria.{{ $name }}" ng-change="ctrl.changeToggle(ctrl.criteria.{{ $name }})">
            <span class="text-nowrap">{{ $title }}</span>
        </label>
    </div>
</div>