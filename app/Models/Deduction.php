<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Deduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'rate',
        'coa_id',
    ];

    public function employeeSalaryDeductions(): HasMany
    {
        return $this->hasMany(EmployeeSalaryDeduction::class, 'deduction_id');
    }

    public function chartOfAccount(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'coa_id');
    }
}
