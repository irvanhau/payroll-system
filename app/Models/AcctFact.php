<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcctFact extends Model
{
    use HasFactory;

    protected $fillable = [
        'coa_id',
        'record_id',
        'debit',
        'credit',
        'posted_date',
        'status'
    ];

    public function chartOfAccount(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'coa_id');
    }

    public function employeeSalaryPeriod(): BelongsTo
    {
        return $this->belongsTo(EmployeeSalaryPeriod::class, 'record_id');
    }

    public static function GetAcctFactCredit($record_id)
    {
        $data = AcctFact::select('coa.account_name', 'coa.account_no', 'acct_facts.debit', 'acct_facts.credit')
            ->leftJoin('chart_of_accounts as coa', 'coa.id', 'acct_facts.coa_id')
            ->where('credit', '!=', 0)
            ->where('record_id', $record_id)
            ->get();

        return $data;
    }

    public static function GetAcctFactDebit($record_id)
    {
        $data = AcctFact::select('coa.account_name', 'coa.account_no', 'acct_facts.debit', 'acct_facts.credit')
            ->leftJoin('chart_of_accounts as coa', 'coa.id', 'acct_facts.coa_id')
            ->where('debit', '!=', 0)
            ->where('record_id', $record_id)
            ->get();

        return $data;
    }

    public static function GetTotalDebit($record_id)
    {
        $data = AcctFact::where('record_id', $record_id)->where('debit', '!=', 0)->sum('debit');

        return $data;
    }

    public static function GetTotalCredit($record_id)
    {
        $data = AcctFact::where('record_id', $record_id)->where('credit', '!=', 0)->sum('credit');

        return $data;
    }
}
