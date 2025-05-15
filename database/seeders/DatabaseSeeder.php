<?php

namespace Database\Seeders;

use App\Models\ChartOfAccountCategory;
use App\Models\User;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $coa_cat_array = array(
            'Cash & Bank',
            'Current Assets',
            'Fixed Assets',
            'Depreciation & Amortization',
            'Current Liabilities',
            'Long Term Liabilities',
            'Equity',
            'Income',
            'Cost Of Sales',
            'Other Income',
            'Expenses',
            'Other Expense',
            'Not Set'
        );

        User::create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password')
        ]);

        foreach ($coa_cat_array as $key => $value) {
            ChartOfAccountCategory::create([
                'name' => $value,
                'sequence' => $key + 1,
                'created_at' => Carbon::now()
            ]);
        }
    }
}
