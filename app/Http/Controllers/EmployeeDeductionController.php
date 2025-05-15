<?php

namespace App\Http\Controllers;

use App\Models\Deduction;
use App\Models\Employee;
use App\Models\EmployeeSalaryDeduction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EmployeeDeductionController extends Controller
{
    public function index($employee_id)
    {
        $employee = Employee::findOrFail($employee_id);
        $deductions = Deduction::all();
        return view('admin.employee.deduction', compact('employee', 'deductions'));
    }

    public function store(Request $request, $employee_id)
    {
        $validate = Validator::make($request->all(), [
            'deduction_id' => 'required|not_in:0',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors()->all()
            ]);
        }

        $employee = Employee::findOrFail($employee_id);
        $deduction = Deduction::findOrFail($request->deduction_id);

        $grossSalary = (int)$employee->salary_amount + (int)$employee->total_salary_allowance;
        $amountDeduction = ($grossSalary * $deduction->rate) / 100;

        DB::beginTransaction();
        try {
            EmployeeSalaryDeduction::create([
                'employee_id' => $employee_id,
                'deduction_id' => $request->deduction_id,
                'amount' => $amountDeduction,
                'created_at' => Carbon::now()
            ]);

            $total_salary_deduction = (int)$employee->total_salary_deduction + $amountDeduction;
            $employee->update([
                'total_salary_deduction' => $total_salary_deduction
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            throw $e;
        }

        return response()->json([
            'message' => 'Created Successfully'
        ]);
    }

    public function destroy($employee_id, $deduction_id)
    {
        DB::beginTransaction();
        try {
            $employeeDeduction = EmployeeSalaryDeduction::where('employee_id', $employee_id)->where('deduction_id', $deduction_id)->first();

            $employee = Employee::findOrFail($employee_id);
            $total_salary_deduction = (int)$employee->total_salary_deduction - (int)$employeeDeduction->amount;
            $employee->update([
                'total_salary_deduction' => $total_salary_deduction
            ]);

            $employeeDeduction->delete();

            Db::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            throw $e;
        }
    }
}
