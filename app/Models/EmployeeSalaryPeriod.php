<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmployeeSalaryPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'periode',
        'year',
        'total_salary',
        'total_salary_allowance',
        'total_salary_deduction',
        'net_salary_amount',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function acctFacts(): HasMany
    {
        return $this->hasMany(ChartOfAccount::class, 'record_id');
    }
}
