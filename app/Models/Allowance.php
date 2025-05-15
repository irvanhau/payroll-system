<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Allowance extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'coa_id'
    ];

    public function chartOfAccount(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'coa_id');
    }

    public function employeeSalaryAllowances(): HasMany
    {
        return $this->hasMany(EmployeeSalaryAllowance::class, 'allowance_id');
    }
}
