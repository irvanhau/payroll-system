<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\Deduction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DeductionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deductions = Deduction::all();
        return view('admin.deduction.index', compact('deductions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $coas = ChartOfAccount::where('status', 1)->get();
        return view('admin.deduction.create', compact('coas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric',
            'coa_id' => 'required|not_in:0'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors()->all()
            ]);
        }

        DB::beginTransaction();
        try {
            Deduction::create([
                'name' => $request->name,
                'rate' => $request->rate,
                'coa_id' => $request->coa_id,
                'created_at' => Carbon::now()
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
        $data = Deduction::findOrFail($id);
        $coas = ChartOfAccount::where('status', 1)->get();
        return view('admin.deduction.edit', compact('coas', 'data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'rate' => 'required|decimal:0,99.99',
            'coa_id' => 'required|not_in:0'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors()->all()
            ]);
        }

        DB::beginTransaction();
        try {
            Deduction::findOrFail($id)->update([
                'name' => $request->name,
                'rate' => $request->rate,
                'coa_id' => $request->coa_id
            ]);

            Db::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            throw $e;
        }

        return response()->json([
            'message' => 'Updated Successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
