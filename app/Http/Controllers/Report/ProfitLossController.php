<?php

namespace App\Http\Controllers\Report;

use App\Exports\ProfitLossExport;
use App\Http\Controllers\Controller;
use App\Models\ChartOfAccount;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProfitLossController extends Controller
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
            $sumMoveRevenues = ChartOfAccount::GetSumMove(intval($month), $year, 'R', '', 'Other Income');
            $sumMoveOtherRevenues = ChartOfAccount::GetSumMove(intval($month), $year, 'R', '', 'Income');
            $sumMoveCoses = ChartOfAccount::GetSumMove(intval($month), $year, 'C', '', 'Other Expense', 'Expenses');
            $sumMoveExpenses = ChartOfAccount::GetSumMove(intval($month), $year, 'C', '', 'Other Expense', 'Cost of Sales');

            $sumBeginRevenues = ChartOfAccount::GetSumBegin(intval($month) - 1, $year, 'R', '', 'Other Income');
            $sumBeginOtherRevenues = ChartOfAccount::GetSumBegin(intval($month) - 1, $year, 'R', '', 'Income');
            $sumBeginCoses = ChartOfAccount::GetSumBegin(intval($month) - 1, $year, 'C', '', 'Other Expense', 'Expenses');
            $sumBeginExpenses = ChartOfAccount::GetSumBegin(intval($month) - 1, $year, 'C', '', 'Other Expense', 'Cost of Sales');
        } else {
            $date = Carbon::now()->format('Y-m');
            $arrayDate = explode("-", $date);
            $year = intval($arrayDate[0]);
            $month = intval($arrayDate[1]);

            $sumMoveRevenues = ChartOfAccount::GetSumMove(intval($month), $year, 'R', '', 'Other Income');
            $sumMoveOtherRevenues = ChartOfAccount::GetSumMove(intval($month), $year, 'R', '', 'Income');
            $sumMoveCoses = ChartOfAccount::GetSumMove(intval($month), $year, 'C', '', 'Other Expense', 'Expenses');
            $sumMoveExpenses = ChartOfAccount::GetSumMove(intval($month), $year, 'C', '', 'Other Expense', 'Cost of Sales');


            $sumBeginRevenues = ChartOfAccount::GetSumBegin(intval($month) - 1, $year, 'R', '', 'Other Income');
            $sumBeginOtherRevenues = ChartOfAccount::GetSumBegin(intval($month) - 1, $year, 'R', '', 'Income');
            $sumBeginCoses = ChartOfAccount::GetSumBegin(intval($month) - 1, $year, 'C', '', 'Other Expense', 'Expenses');
            $sumBeginExpenses = ChartOfAccount::GetSumBegin(intval($month) - 1, $year, 'C', '', 'Other Expense', 'Cost of Sales');
        }

        $levelOneRevenues = ChartOfAccount::GetLevelOne('R', '', 'Other Income');
        $levelTwoRevenues = ChartOfAccount::GetLevelTwo('R', '', 'Other Income');

        $levelOneOtherRevenues = ChartOfAccount::GetLevelOne('R', '', 'Income');
        $levelTwoOtherRevenues = ChartOfAccount::GetLevelTwo('R', '', 'Income');

        $levelOneCoses = ChartOfAccount::GetLevelOne('C', '', 'Other Expense', 'Expenses');
        $levelTwoCoses = ChartOfAccount::GetLevelTwo('C', '', 'Other Expense', 'Expenses');

        $levelOneExpenses = ChartOfAccount::GetLevelOne('C', '', 'Other Expense', 'Cost of Sales');
        $levelTwoExpenses = ChartOfAccount::GetLevelTwo('C', '', 'Other Expenses', 'Cost of Sales');

        return view('admin.report.profit_loss', compact('levelOneRevenues', 'levelTwoRevenues', 'levelOneOtherRevenues', 'levelTwoOtherRevenues', 'levelOneCoses', 'levelTwoCoses', 'levelOneExpenses', 'levelTwoExpenses', 'sumMoveRevenues', 'sumMoveOtherRevenues', 'sumMoveCoses', 'sumMoveExpenses', 'sumBeginRevenues', 'sumBeginOtherRevenues', 'sumBeginCoses', 'sumBeginExpenses', 'date'));
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
            "levelOneRevenues" => ChartOfAccount::GetLevelOne('R', '', 'Other Income'),
            "levelTwoRevenues" => ChartOfAccount::GetLevelTwo('R', '', 'Other Income'),
            "levelOneOtherRevenues" => ChartOfAccount::GetLevelOne('R', '', 'Income'),
            "levelTwoOtherRevenues" => ChartOfAccount::GetLevelTwo('R', '', 'Income'),
            "levelOneCoses" => ChartOfAccount::GetLevelOne('C', '', 'Other Expense', 'Expenses'),
            "levelTwoCoses" => ChartOfAccount::GetLevelTwo('C', '', 'Other Expense', 'Expenses'),
            "levelOneExpenses" => ChartOfAccount::GetLevelOne('C', '', 'Other Expense', 'Cost of Sales'),
            "levelTwoExpenses" => ChartOfAccount::GetLevelTwo('C', '', 'Other Expenses', 'Cost of Sales'),

            "sumMoveRevenues" => ChartOfAccount::GetSumMove($month, $year, 'R', '', 'Other Income'),
            "sumBeginRevenues" => ChartOfAccount::GetSumBegin($month - 1, $year, 'R', '', 'Other Income'),
            "sumMoveOtherRevenues" => ChartOfAccount::GetSumMove($month, $year, 'R', '', 'Income'),
            "sumBeginOtherRevenues" => ChartOfAccount::GetSumBegin($month - 1, $year, 'R', '', 'Income'),
            "sumMoveCoses" => ChartOfAccount::GetSumMove($month, $year, 'C', '', 'Other Expense', 'Expense'),
            "sumBeginCoses" => ChartOfAccount::GetSumBegin($month - 1, $year, 'C', '', 'Other Expense', 'Expense'),
            "sumMoveExpenses" => ChartOfAccount::GetSumMove($month, $year, 'C', '', 'Other Expense', 'Cost of Sales'),
            "sumBeginExpenses" => ChartOfAccount::GetSumBegin($month - 1, $year, 'C', '', 'Other Expense', 'Cost of Sales'),
        ];

        $pdf = Pdf::loadView('admin.download.profit_loss', $data);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'profit-loss-' . $this->monthName[$month - 1] . $year . '.pdf');
    }

    public function generateExcel(Request $request)
    {
        $date = $request->date;
        $arrayDate = explode("-", $date);
        $year = intval($arrayDate[0]);
        $month = intval($arrayDate[1]);

        return Excel::download(new ProfitLossExport($month, $year), 'profit-loss-' . $this->monthName[$month - 1] . $year . ".xlsx");
    }
}
