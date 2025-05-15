<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;

class ChartOfAccount extends Model
{
    use HasFactory;
    protected $fillable = [
        'account_no',
        'account_name',
        'coa_category_id',
        'account_category_name',
        'account_type',
        'status'
    ];

    public function chartOfAccountCategory(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccountCategory::class, 'coa_category_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function allowances(): HasMany
    {
        return $this->hasMany(Allowance::class);
    }


    public function deductions(): HasMany
    {
        return $this->hasMany(Deduction::class);
    }


    public function acctFacts(): HasMany
    {
        return $this->hasMany(AcctFact::class);
    }

    // ASSETS
    public static function GetLevelOne($type1, $type2 = '', $notIn1 = '', $notIn2 = '')
    {
        try {
            $data = DB::table('chart_of_accounts as coa')
                ->select('cc.name', 'cc.sequence')
                ->join('chart_of_account_categories as cc', 'cc.id', 'coa.coa_category_id')
                ->whereIn('account_type', [$type1, $type2])
                ->when($notIn1, function (Builder $query, string $notIn1) {
                    $query->whereNotIn('cc.name', [$notIn1]);
                })
                ->when($notIn2, function (Builder $query, string $notIn2) {
                    $query->whereNotIn('cc.name', [$notIn2]);
                })->groupBy('cc.sequence', 'cc.name')->orderBy('cc.sequence')->get();

            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public static function GetLevelTwo($type1, $type2 = '', $notIn1 = '', $notIn2 = '')
    {
        try {
            $data = DB::table('chart_of_accounts as coa')
                ->select('cc.name', 'coa.account_no', 'coa.account_name')
                ->join('chart_of_account_categories as cc', 'cc.id', 'coa.coa_category_id')
                ->whereIn('account_type', [$type1, $type2])
                ->when($notIn1, function (Builder $query, string $notIn1) {
                    $query->whereNotIn('cc.name', [$notIn1]);
                })
                ->when($notIn2, function (Builder $query, string $notIn2) {
                    $query->whereNotIn('cc.name', [$notIn2]);
                })->groupBy('cc.name', 'coa.account_no', 'coa.account_name')->orderBy('coa.account_no')->get();

            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public static function GetSumMove($month, $year, $type1, $type2 = '', $notIn1 = '', $notIn2 = '')
    {
        $data = DB::select("SELECT coa.account_no , sum(af.debit) as debit, sum(af.credit) as credit
                    FROM chart_of_accounts as coa
                    LEFT JOIN acct_facts as af ON af.coa_id = coa.id
                    WHERE coa.account_type IN ('$type1', '$type2')
                    AND coa.account_category_name NOT IN ('$notIn1','$notIn2')
                    AND MONTH(af.posted_date) = $month
                    AND YEAR(af.posted_date) = $year
                    GROUP BY coa.account_no
                    ORDER BY coa.account_no ASC;");

        return $data;
    }

    public static function GetSumBegin($month, $year, $type1, $type2 = '', $notIn1 = '', $notIn2 = '')
    {
        $data = DB::select("SELECT coa.account_no , sum(af.debit) as debit, sum(af.credit) as credit
                    FROM chart_of_accounts as coa
                    LEFT JOIN acct_facts as af ON af.coa_id = coa.id
                    WHERE coa.account_type IN ('$type1', '$type2')
                    AND coa.account_category_name NOT IN ('$notIn1','$notIn2')
                    AND MONTH(af.posted_date) = $month
                    AND YEAR(af.posted_date) = $year
                    GROUP BY coa.account_no
                    ORDER BY coa.account_no ASC;");

        return $data;
    }

    public static function GetDataAccount($category)
    {
        $data = ChartOfAccount::select('chart_of_accounts.account_name', 'chart_of_accounts.account_no');

        if ($category != 14) {
            $data->where('chart_of_accounts.coa_category_id', $category);
        }

        return $data->orderBy('account_no')->get();
    }

    public static function GetReport($category, $month, $year)
    {
        $data = ChartOfAccount::select('acct_facts.posted_date', 'chart_of_accounts.account_name', 'acct_facts.debit', 'acct_facts.credit')
            ->join('acct_facts', 'acct_facts.coa_id', 'chart_of_accounts.id')
            ->join('chart_of_account_categories', 'chart_of_account_categories.id', 'chart_of_accounts.coa_category_id')
            ->whereMonth('acct_facts.posted_date', $month)
            ->whereYear('acct_facts.posted_date', $year);

        if ($category != 14) {
            $data->where('chart_of_accounts.coa_category_id', $category);
        }
        return $data->orderBy('posted_date')->get();
    }


    public static function GetDataBegin($category, $month, $year)
    {
        $data = DB::table('chart_of_accounts')
            ->selectRaw('chart_of_accounts.account_name,SUM(acct_facts.debit) as total_debit, SUM(acct_facts.credit) as total_credit')
            ->leftJoin('acct_facts', 'acct_facts.coa_id', 'chart_of_accounts.id')
            ->leftJoin('chart_of_account_categories', 'chart_of_account_categories.id', 'chart_of_accounts.coa_category_id');
        if ($month == 1) {
            $data
                ->whereMonth('acct_facts.posted_date', $month)
                ->whereYear('acct_facts.posted_date', $year - 1);
        } else {
            $data
                ->whereMonth('acct_facts.posted_date', $month - 1)
                ->whereYear('acct_facts.posted_date', $year);
        }

        if ($category != 14) {
            $data->where('chart_of_accounts.coa_category_id', $category);
        }
        return $data->groupBy('account_name')->get();
    }
}
