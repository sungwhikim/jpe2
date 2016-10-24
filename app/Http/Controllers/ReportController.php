<?php
namespace App\Http\Controllers;

use App\Models\UserFunction;
use Illuminate\Http\Request;
use App\Models\Report;
use Maatwebsite\Excel\Facades\Excel;


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

        //change output based on type.  Excel gets different process.
        //view and print
        if( $action != 'excel' )
        {
            return response()->view('reports.' . $report_name . '-' . $action, ['report' => $report, 'report_data' => $report_data]);
        }

        //excel
        else
        {
            Excel::create('Filename', function($excel)
            {

                // Set the title
                $excel->setTitle('Our new awesome title');

                // Chain the setters
                $excel->setCreator('Maatwebsite')
                    ->setCompany('Maatwebsite');

                // Call them separately
                $excel->setDescription('A demonstration to change the file properties');

                $excel->sheet('Sheetname', function($sheet) {

                    $sheet->row(1, array(
                        'test1', 'test2'
                    ));

                });

            })->store('xlsx', storage_path('temp'));

            /*
            Excel::create('New file', function($excel) use($report_name, $action, $report, $report_data) {

                $excel->sheet('New sheet', function($sheet) use($report_name, $action, $report, $report_data) {

                    $sheet->fromArray($report_data['body']);

                    //$sheet->loadView('reports.' . $report_name . '-' . $action, ['report' => $report, 'report_data' => $report_data]);

                });

            })->download('xlsx');
            */

            /* We are not using the built in download function in the Excel component as it returns a zero byte file for
               xlsx. xls works fine...  There was some stuff about extra spaces before php opening tags, but even if that is the issue
               and did find them all, it might crop up again and it isn't a guaranty of fixing the issues today.  So, we are
               saving the file first, then streaming it out.  We will delete all files older than 10 minutes before each download
            */
            return $this->downloadExcelFile('Filename.xlsx', 'Download1.xlsx');
        }
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

    public function deleteOldExcelFiles($path)
    {
        foreach( glob($path . '*.xlsx') as $file )
        {

        }
    }

    /**
     * We are using this method to download the excel file rather than using the built in function as it doesn't work for
     * xlsx files.  It produces zero byte files.
     *
     * @param $server_file_name
     * @param $download_name
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadExcelFile($server_file_name, $download_name, $path = null)
    {
        //get path
        $path = ( $path === null ) ? base_path(env('APP_EXCEL_DOWNLOAD_FILE_PATH')) : $path;

        //delete files
        $this->deleteOldExcelFiles($path);

        //set header
        $headers = array(
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        );

        return response()->download($path . $server_file_name, $download_name, $headers);
    }
}