<?php

namespace App\Http\Controllers\Report;

use App\Exports\GeneralLedgerExport;
use App\Http\Controllers\Controller;
use App\Models\ChartOfAccount;
use App\Models\ChartOfAccountCategory;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class GeneralLedgerController extends Controller
{
    public $monthName = [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December',
    ];

    public function index(Request $request)
    {
        $dataCategory = ChartOfAccountCategory::all();

        if ($request->has('date') && $request->has('category_id') && $request->date != '' && $request->category_id != 0) {
            $date = $request->date;
            $category = $request->category_id;
            $arrayDate = explode("-", $date);
            $year = intval($arrayDate[0]);
            $month = intval($arrayDate[1]);

            $dataAccount = ChartOfAccount::GetDataAccount($category);
            $dataReport = ChartOfAccount::GetReport($category, $month, $year);
            $dataBegin = ChartOfAccount::GetDataBegin($category, $month, $year);
        } else {
            $date = Carbon::now()->format('Y-m');
            $arrayDate = explode("-", $date);
            $year = intval($arrayDate[0]);
            $month = intval($arrayDate[1]);
            $category = 1;

            $dataAccount = ChartOfAccount::GetDataAccount($category);
            $dataReport = ChartOfAccount::GetReport($category, $month, $year);
            $dataBegin = ChartOfAccount::GetDataBegin($category, $month, $year);
        }

        return view('admin.report.general_ledger', compact('date', 'category', 'dataCategory', 'dataAccount', 'dataReport', 'dataBegin'));
    }

    public function generatePdf(Request $request)
    {
        $category_id = $request->category_id;

        if ($category_id != 14) {
            $category = ChartOfAccount::findOrFail($category_id);
            $categoryName = $category->name;
        } else {
            $categoryName = "All";
        }

        $date = $request->date;
        $arrayDate = explode("-", $date);
        $year = intval($arrayDate[0]);
        $month = intval($arrayDate[1]);
        $data = [
            'month' => $this->monthName[$month - 1],
            'year' => $year,
            'dataAccount' => ChartOfAccount::GetDataAccount($category_id),
            'dataReport' => ChartOfAccount::GetReport($category_id, $month, $year),
            'dataBegin' => ChartOfAccount::GetDataBegin($category_id, $month, $year),
        ];

        $pdf = Pdf::loadView('admin.download.general_ledger', $data);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'general-ledger-' . $categoryName . '-'  . $this->monthName[$month - 1] . $year . '.pdf');
    }

    public function generateExcel(Request $request)
    {
        $category_id = $request->category_id;

        if ($category_id != 14) {
            $category = ChartOfAccount::findOrFail($category_id);
            $categoryName = $category->name;
        } else {
            $categoryName = "All";
        }

        $date = $request->date;
        $arrayDate = explode("-", $date);
        $year = intval($arrayDate[0]);
        $month = intval($arrayDate[1]);

        return Excel::download(new GeneralLedgerExport($category_id, $month, $year), 'general-ledger-' . $categoryName . '-' . $this->monthName[$month - 1] . $year . ".xlsx");
    }
}
