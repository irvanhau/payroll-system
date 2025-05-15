<?php

namespace App\Http\Controllers;

use App\Models\Allowance;
use App\Models\ChartOfAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AllowanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allowances = Allowance::all();
        return view('admin.allowance.index', compact('allowances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $coas = ChartOfAccount::where('status', 1)->get();
        return view('admin.allowance.create', compact('coas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'coa_id' => 'required|not_in:0'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors()->all()
            ]);
        }

        DB::beginTransaction();
        try {
            Allowance::create([
                'name' => $request->name,
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
        $data = Allowance::findOrFail($id);
        $coas = ChartOfAccount::where('status', 1)->get();
        return view('admin.allowance.edit', compact('coas', 'data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'coa_id' => 'required|not_in:0'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors()->all()
            ]);
        }

        DB::beginTransaction();
        try {
            Allowance::findOrFail($id)->update([
                'name' => $request->name,
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
}
