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
        //get data and criteria
        $report_model = new Report();
        $report_method = $this->changeToMethodName($report_name);
        $criteria = $report_model->getCriteria();
        $report_data['body'] = $report_model->$report_method($request);
        $report_data['criteria'] = $criteria;

        //set report title
        $report_data['title'] = $this->getReportTitle($report_name);
debugbar()->info($report_data['body']->uom_data);
debugbar()->info($report_data['body']);
        //generate view
        return response()->view('reports.' . $report_name . '-' . $action, ['report' => $report_data]);
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
