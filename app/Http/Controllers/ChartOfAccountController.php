<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\ChartOfAccountCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ChartOfAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chart_of_accounts = ChartOfAccount::all();
        return view('admin.chart_of_account.index', compact('chart_of_accounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $coa_categories = ChartOfAccountCategory::all();
        return view('admin.chart_of_account.create', compact('coa_categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'account_name' => 'required',
            'account_no' => 'required',
            'coa_category_id' => 'required|not_in:0'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors()->all()
            ]);
        }

        $categoryName = ChartOfAccountCategory::findOrFail($request->coa_category_id);
        $accountNo = substr($request->account_no, 0, 1);
        switch ($accountNo) {
            case 1:
                $account_type = 'A';
                break;
            case 2:
                $account_type = 'L';
                break;
            case 3:
                $account_type = 'E';
                break;
            case 4:
                $account_type = 'R';
                break;
            case 5:
            case 6:
                $account_type = 'C';
                break;
            default:
                $account_type = 'N';
                break;
        }

        DB::beginTransaction();
        try {
            ChartOfAccount::create([
                'account_no' => $request->account_no,
                'account_name' => $request->account_name,
                'coa_category_id' => $request->coa_category_id,
                'account_category_name' => $categoryName->name,
                'account_type' => $account_type,
                'status' => 1,
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
        $coa_categories = ChartOfAccountCategory::all();
        $data = ChartOfAccount::findOrFail($id);
        return view('admin.chart_of_account.edit', compact('data', 'coa_categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $categoryName = ChartOfAccountCategory::findOrFail($request->coa_category_id);
        $accountNo = substr($request->account_no, 0, 1);
        switch ($accountNo) {
            case 1:
                $account_type = 'A';
                break;
            case 2:
                $account_type = 'L';
                break;
            case 3:
                $account_type = 'E';
                break;
            case 4:
                $account_type = 'R';
                break;
            case 5:
            case 6:
                $account_type = 'C';
                break;
            default:
                $account_type = 'N';
                break;
        }

        $coa = ChartOfAccount::findOrFail($id);
        $checkCOA = ChartOfAccount::where('account_no', $request->account_no)->first();

        if (!empty($checkCOA) && $checkCOA->id != $coa->id) {
            return response()->json([
                'errors' => "Account No Already Used"
            ]);
        }
        $coa->update([
            'account_no' => $request->account_no,
            'account_name' => $request->account_name,
            'coa_category_id' => $request->coa_category_id,
            'account_category_name' => $categoryName->name,
            'account_type' => $account_type,
        ]);

        return response()->json([
            'message' => 'Updated Successfully',
        ]);
    }

    public function setStatus($id)
    {
        $coa = ChartOfAccount::findOrFail($id);

        $active = $coa->status == 1 ? 0 : 1;


        $success = $coa->update([
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
}
