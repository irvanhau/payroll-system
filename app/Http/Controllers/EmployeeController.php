<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Models\ChartOfAccount;
use App\Models\Employee;
use App\Models\EmployeeSalaryDeduction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::all();
        return view('admin.employee.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $coas = ChartOfAccount::where('status', 1)->get();
        return view('admin.employee.create', compact('coas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
            ],
            'gender' => ['required', 'in:L,P'],
            'birth_date' => ['required', 'date'],
            'birth_place' => ['required', 'string'],
            'religion' => ['required', 'string', 'not_in:0'],
            'address' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:15'],
            'occupation' => ['required', 'string', 'max:100'],
            'coa_id' => ['required'],
            'salary_amount' => ['required']
        ];

        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors()->all()
            ]);
        }

        DB::beginTransaction();
        try {
            Employee::create([
                'name' => $request->name,
                'email' => $request->email,
                'gender' => $request->gender,
                'birth_date' => $request->birth_date,
                'birth_place' => $request->birth_place,
                'religion' => $request->religion,
                'address' => $request->address,
                'npwp' => $request->npwp,
                'phone_number' => $request->phone_number,
                'occupation' => $request->occupation,
                'salary_amount' => $request->salary_amount,
                'status' => 1,
                'coa_id' => $request->coa_id,
                'created_at' => Carbon::now(),
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $employee = Employee::findOrFail($id);
        return view('admin.employee.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Employee::findOrFail($id);
        $coas = ChartOfAccount::where('status', 1)->get();
        return view('admin.employee.edit', compact('data', 'coas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
            ],
            'gender' => ['required', 'in:L,P'],
            'birth_date' => ['required', 'date'],
            'birth_place' => ['required', 'string'],
            'religion' => ['required', 'string', 'not_in:0'],
            'address' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:15'],
            'occupation' => ['required', 'string', 'max:100'],
            'coa_id' => ['required'],
            'salary_amount' => ['required']
        ];

        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors()->all()
            ]);
        }

        DB::beginTransaction();
        try {
            Employee::findOrFail($id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'gender' => $request->gender,
                'birth_date' => $request->birth_date,
                'birth_place' => $request->birth_place,
                'religion' => $request->religion,
                'address' => $request->address,
                'npwp' => $request->npwp,
                'phone_number' => $request->phone_number,
                'occupation' => $request->occupation,
                'salary_amount' => $request->salary_amount,
                'coa_id' => $request->coa_id,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            throw $e;
        }

        return response()->json([
            'message' => 'Updated Successfully'
        ]);
    }

    public function setStatus($id)
    {
        $employee = Employee::findOrFail($id);

        $active = $employee->status == "Active" ? 0 : 1;

        $success = $employee->update([
            'status' => $active
        ]);

        if ($success == 1) {
            $notif = array(
                'message' => 'Data Activate Successfully',
                'alert-type' => 'info'
            );
        } else {
            $notif = array(
                'message' => 'Data Non Activate Successfully',
                'alert-type' => 'info'
            );
        }

        return redirect()->back()->with($notif);
    }

    public function generateNetSalary($id)
    {
        $employee = Employee::findOrFail($id);

        // Gross Salary
        $grossSalary = $employee->salary_amount + $employee->total_salary_allowance;

        // Deduction
        foreach ($employee->employeeSalaryDeductions as $item) {
            $amountDeduction = ($grossSalary * $item->deduction->rate) / 100;

            DB::beginTransaction();
            try {
                EmployeeSalaryDeduction::findOrFail($item->id)->update([
                    'amount' => $amountDeduction,
                ]);

                $total_salary_deduction = ((int)$employee->total_salary_deduction - $item->amount) +  $amountDeduction;
                $employee->update([
                    'total_salary_deduction' => $total_salary_deduction
                ]);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                dd($e);
                throw $e;
            }
        }

        $netSalary = $grossSalary - $employee->total_salary_deduction;

        DB::beginTransaction();
        try {
            $employee->update([
                'net_salary_amount' => $netSalary
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            throw $e;
        }

        $notif = array(
            'message' => 'Generate Net Salary Successfully',
            'alert-type' => 'info'
        );

        return redirect()->back()->with($notif);
    }
}
