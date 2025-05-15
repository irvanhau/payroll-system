<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function employeeSalaryAllowances(): HasMany
    {
        return $this->hasMany(EmployeeSalaryAllowance::class);
    }

    public function employeeSalaryDeductions(): HasMany
    {
        return $this->hasMany(EmployeeSalaryDeduction::class);
    }

    public function chartOfAccount(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'coa_id');
    }

    public function employeeSalaryPeriods(): HasMany
    {
        return $this->hasMany(EmployeeSalaryPeriod::class);
    }

    public function getBirthDateAttribute($value)
    {
        return Carbon::parse($value)->format('d F Y');
    }

    public function getGenderAttribute($value)
    {
        if ($value == "L") {
            return "Male";
        } else {
            return "Female";
        }
    }

    public function getStatusAttribute($value)
    {
        return $value == 1 ? 'Active' : 'Non Active';
    }
}
