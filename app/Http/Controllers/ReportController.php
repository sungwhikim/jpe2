<?php
namespace App\Http\Controllers;

use App\Models\UserFunction;
use Illuminate\Http\Request;
use App\Models\Report;


class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getIndex($report_name)
    {
        //get report title
        $data['title'] = $this->getReportTitle($report_name);

        //generate view
        return response()->view('reports.' . $report_name, ['report' => $data]);
    }

    public function getReport(Request $request, $report_name, $action)
    {
        //set report title
        $report['title'] = $this->getReportTitle($report_name);

        //get data and criteria
        $report_model = new Report();
        $report_method = $this->changeToMethodName($report_name);
        $paginate = ( $action == 'view' ) ? true : false; //we only need to paginate for display
        $report_data = $report_model->$report_method($request, $paginate);
        $report['criteria'] = $report_model->getCriteria();

        //if it is anything other than view, then get the display friendly criteria
        if( $action != 'view' ) { $report['criteria_display'] = $report_model->getCriteriaDisplay($report['criteria']); }
debugbar()->info($report_data);
        //generate view
        return response()->view('reports.' . $report_name . '-' . $action, ['report' => $report, 'report_data' => $report_data]);
    }

    private function getReportTitle($report_name)
    {
        return UserFunction::where('url', 'ILIKE', '%' . $report_name)->pluck('name');
    }

    private function changeToMethodName($report_name)
    {
        //first get rid of the dashes and upper case all the words
        $report_name = ucwords(str_replace('-', ' ', $report_name));

        //get rid of the spaces
        $report_name = str_replace(' ', '', $report_name);

        //change first character to lower case
        $report_name = lcfirst($report_name);

        return $report_name;
    }
}
