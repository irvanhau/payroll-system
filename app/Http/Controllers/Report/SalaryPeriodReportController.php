<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\AcctFact;
use App\Models\EmployeeSalaryPeriod;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SalaryPeriodReportController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('date') && $request->date != '') {
            $date = $request->date;
            $arrayDate = explode("-", $date);
            $year = intval($arrayDate[0]);
            $month = intval($arrayDate[1]);

            $data = EmployeeSalaryPeriod::where('periode', $month)->where('year', $year)->get();
        } else {
            $date = Carbon::now()->format('Y-m');
            $arrayDate = explode("-", $date);
            $year = intval($arrayDate[0]);
            $month = intval($arrayDate[1]);

            $data = EmployeeSalaryPeriod::where('periode', $month)->where('year', $year)->get();
        }
        return view('admin.report.salary_period', compact('date', 'data'));
    }

    public function viewJurnal($record_id)
    {
        // DEBIT
        $acct_fact_debit = AcctFact::GetAcctFactDebit($record_id);
        $acct_fact_credit = AcctFact::GetAcctFactCredit($record_id);

        $total_debit = AcctFact::GetTotalDebit($record_id);
        $total_credit = AcctFact::GetTotalCredit($record_id);

        return response()->json(
            [
                'acct_fact_debit' => $acct_fact_debit,
                'acct_fact_credit' => $acct_fact_credit,
                'total_debit' => $total_debit,
                'total_credit' => $total_credit
            ]
        );
    }
}
