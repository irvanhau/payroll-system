<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use App\Jobs\SendMailJob;
use App\Models\AcctFact;
use App\Models\ChartOfAccount;
use App\Models\Employee;
use App\Models\EmployeeSalaryPeriod;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EmployeeSalaryPeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = Employee::all();
        $coaCash = ChartOfAccount::where('coa_category_id', 1)->get();
        return view('admin.employee_salary_period.index', compact('data', 'coaCash'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $arrayDate = explode("-", $request->date);
        $year = intval($arrayDate[0]);
        $month = intval($arrayDate[1]);

        $employees = Employee::where('status', 1)->get();

        $employeeSalaryPeriod = EmployeeSalaryPeriod::where('periode', $month)->where('year', $year)->count();
        if ($employeeSalaryPeriod != 0) {
            return response()->json([
                'errors' => 'Please Generate In Next Month'
            ]);
        }

        foreach ($employees as $employee) {

            DB::beginTransaction();
            try {
                $employeeSalaryPeriod = EmployeeSalaryPeriod::create([
                    'employee_id' => $employee->id,
                    'periode' => $month,
                    'year' => $year,
                    'total_salary' => $employee->salary_amount,
                    'total_salary_allowance' => $employee->total_salary_allowance,
                    'total_salary_deduction' => $employee->total_salary_deduction,
                    'net_salary_amount' => $employee->net_salary_amount,
                ]);

                AcctFact::create([
                    'coa_id' => $employee->coa_id,
                    'record_id' => $employeeSalaryPeriod->id,
                    'debit' => $employee->salary_amount,
                    'credit' => 0,
                    'posted_date' => Carbon::now(),
                    'status' => 1
                ]);

                foreach ($employee->employeeSalaryAllowances as $employeeSalaryAllowance) {
                    AcctFact::create([
                        'coa_id' => $employeeSalaryAllowance->allowance->coa_id,
                        'record_id' => $employeeSalaryPeriod->id,
                        'debit' => $employeeSalaryAllowance->amount,
                        'credit' => 0,
                        'posted_date' => Carbon::now(),
                        'status' => 1
                    ]);
                }

                foreach ($employee->employeeSalaryDeductions as $employeeSalaryDeduction) {
                    AcctFact::create([
                        'coa_id' => $employeeSalaryDeduction->deduction->coa_id,
                        'record_id' => $employeeSalaryPeriod->id,
                        'debit' => 0,
                        'credit' => $employeeSalaryDeduction->amount,
                        'posted_date' => Carbon::now(),
                        'status' => 1
                    ]);
                }

                AcctFact::create([
                    'coa_id' => $request->coa_cash_id,
                    'record_id' => $employeeSalaryPeriod->id,
                    'debit' => 0,
                    'credit' => $employee->net_salary_amount,
                    'posted_date' => Carbon::now(),
                    'status' => 1
                ]);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                dd($e);
                return response()->json([
                    'errors' => 'Internal Server Error'
                ]);
            }

            $salaryDetails = [
                'month_year' => Carbon::parse($request->date)->format('F Y'),
                'basic_salary' => $employee->salary_amount,
                'allowance' => $employee->total_salary_allowance,
                'deduction' => $employee->total_salary_deduction,
                'net_salary' => $employee->net_salary_amount
            ];

            $name = str_replace(" ", "_", $employee->name);
            $pdf = PDF::loadView('admin.emails.salary_slip', compact('employee', 'salaryDetails'));
            $fileName = 'slip_' . $name . '_' . time() . '.pdf';
            $pdf->save(public_path('upload/' . $fileName));

            SendMailJob::dispatch($employee, $salaryDetails, public_path() . "/upload/" . $fileName);
        }
        return response()->json(['message' => 'Generate Salary Successfully!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
