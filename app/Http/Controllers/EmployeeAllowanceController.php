<?php

namespace App\Http\Controllers;

use App\Models\Allowance;
use App\Models\Employee;
use App\Models\EmployeeSalaryAllowance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EmployeeAllowanceController extends Controller
{
    public function index($employee_id)
    {
        $employee = Employee::findOrFail($employee_id);
        $allowances = Allowance::all();
        return view('admin.employee.allowance', compact('employee', 'allowances'));
    }

    public function store(Request $request, $employee_id)
    {
        $validate = Validator::make($request->all(), [
            'allowance_id' => 'required|not_in:0',
            'amount' => 'required|integer'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors()->all()
            ]);
        }

        DB::beginTransaction();
        try {
            EmployeeSalaryAllowance::create([
                'employee_id' => $employee_id,
                'allowance_id' => $request->allowance_id,
                'amount' => $request->amount,
                'created_at' => Carbon::now()
            ]);

            $employee = Employee::findOrFail($employee_id);
            $total_salary_allowance = (int)$employee->total_salary_allowance + (int)$request->amount;
            $employee->update([
                'total_salary_allowance' => $total_salary_allowance
            ]);

            Db::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            throw $e;
        }

        return response()->json([
            'message' => 'Created Successfully'
        ]);
    }

    public function destroy($employee_id, $allowance_id)
    {
        DB::beginTransaction();
        try {
            $employeeAllowance = EmployeeSalaryAllowance::where('employee_id', $employee_id)->where('allowance_id', $allowance_id)->first();

            $employee = Employee::findOrFail($employee_id);
            $total_salary_allowance = (int)$employee->total_salary_allowance - (int)$employeeAllowance->amount;
            $employee->update([
                'total_salary_allowance' => $total_salary_allowance
            ]);

            $employeeAllowance->delete();

            Db::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            throw $e;
        }
    }
}
