<div class="col-lg-2 col-md-2 col-sm-2 col-xs-3 report-control form-group"
     ng-class="{ 'has-error': (reportForm.year.$touched || reportForm.$submitted) && reportForm.year.$invalid }">
    <label class="control-label">Year <span class="required-field glyphicon glyphicon-asterisk" /></label>
    <?php $current_year = date('Y'); ?>
    <select class="form-control" name="year" ng-model="ctrl.criteria.year" required>
        @for( $year_counter = 0; $year_counter < $num_years; $year_counter++ )
            <option value="{{ $current_year - $year_counter }}">{{ $current_year - $year_counter }}</option>
        @endfor
    </select>
    <!-- ngMessages goes here -->
    <div class="help-block ng-messages" ng-messages="reportForm.year.$error"
         ng-if="reportForm.$submitted || reportForm.year.$touched" ng-cloak>
        @include('layouts.validate-message')
    </div>
</div>