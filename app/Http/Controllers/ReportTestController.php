<?php
namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;

class ReportTestController extends Controller
{
    public function getIndex()
    {
        ob_end_clean();
        ob_start();

        Excel::create('Filename1', function($excel)
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

        $headers = array(
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        );

        return response()->download(base_path('storage/temp/') . 'Filename1.xlsx', 'Download1.xlsx', $headers);

    }
}