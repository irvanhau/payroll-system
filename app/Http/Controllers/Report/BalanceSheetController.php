<?php

namespace App\Http\Controllers\Report;

use App\Exports\BalanceSheetExport;
use App\Http\Controllers\Controller;
use App\Models\ChartOfAccount;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BalanceSheetController extends Controller
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
        if ($request->has('date') && $request->date != '') {
            $date = $request->date;
            $arrayDate = explode("-", $date);
            $year = intval($arrayDate[0]);
            $month = intval($arrayDate[1]);
            $sumMoveAssets = ChartOfAccount::GetSumMove(intval($month), $year, 'A');
            $sumBeginAssets = ChartOfAccount::GetSumBegin(intval($month) - 1, $year, 'A');
            $sumMoveLiaEqus = ChartOfAccount::GetSumMove(intval($month), $year, 'L', 'E');
            $sumBeginLiaEqus = ChartOfAccount::GetSumBegin(intval($month) - 1, $year, 'L', 'E');
        } else {
            $date = Carbon::now()->format('Y-m');
            $arrayDate = explode("-", $date);
            $year = intval($arrayDate[0]);
            $month = intval($arrayDate[1]);
            $sumMoveAssets = ChartOfAccount::GetSumMove($month, $year, 'A');
            $sumBeginAssets = ChartOfAccount::GetSumBegin($month - 1, $year, 'A');
            $sumMoveLiaEqus = ChartOfAccount::GetSumMove($month, $year, 'L', 'E');
            $sumBeginLiaEqus = ChartOfAccount::GetSumBegin($month - 1, $year, 'L', 'E');
        }

        $levelOneAssets = ChartOfAccount::GetLevelOne('A');
        $levelTwoAssets = ChartOfAccount::GetLevelTwo('A');

        $levelOneLiaEqus = ChartOfAccount::GetLevelOne('L', 'E');
        $levelTwoLiaEqus = ChartOfAccount::GetLevelTwo('L', 'E');

        return view('admin.report.balance_sheet', compact('levelOneAssets', 'levelTwoAssets', 'levelOneLiaEqus', 'levelTwoLiaEqus', 'sumMoveAssets', 'sumBeginAssets', 'sumMoveLiaEqus', 'sumBeginLiaEqus', 'date'));
    }

    public function generatePdf(Request $request)
    {
        $date = $request->date;
        $arrayDate = explode("-", $date);
        $year = intval($arrayDate[0]);
        $month = intval($arrayDate[1]);
        $data = [
            'year' => $year,
            'month' => $this->monthName[$month - 1],
            'sumMoveAssets' => ChartOfAccount::GetSumMove(intval($month), $year, 'A'),
            'sumBeginAssets' => ChartOfAccount::GetSumBegin(intval($month) - 1, $year, 'A'),
            'sumMoveLiaEqus' => ChartOfAccount::GetSumMove(intval($month), $year, 'L', 'E'),
            'sumBeginLiaEqus' => ChartOfAccount::GetSumBegin(intval($month) - 1, $year, 'L', 'E'),
            'levelOneAssets' => ChartOfAccount::GetLevelOne('A'),
            'levelTwoAssets' => ChartOfAccount::GetLevelTwo('A'),
            'levelOneLiaEqus' => ChartOfAccount::GetLevelOne('L', 'E'),
            'levelTwoLiaEqus' => ChartOfAccount::GetLevelTwo('L', 'E'),
        ];

        $pdf = Pdf::loadView('admin.download.balance_sheet', $data);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'balance-sheet-' . $this->monthName[$month - 1] . $year . '.pdf');
    }

    public function generateExcel(Request $request)
    {
        $date = $request->date;
        $arrayDate = explode("-", $date);
        $year = intval($arrayDate[0]);
        $month = intval($arrayDate[1]);

        return Excel::download(new BalanceSheetExport($month, $year), 'balance-sheet-' . $this->monthName[$month - 1] . $year . ".xlsx");
    }
}
