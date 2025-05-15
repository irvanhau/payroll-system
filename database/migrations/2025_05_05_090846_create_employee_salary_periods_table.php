<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employee_salary_periods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->string('periode');
            $table->string('year');
            $table->decimal('total_salary', 10, 2);
            $table->decimal('total_salary_allowance', 10, 2);
            $table->decimal('total_salary_deduction', 10, 2);
            $table->decimal('net_salary_amount', 10, 2);
            $table->timestamps();

            $table->foreign('employee_id')->on('employees')->references('id')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_salary_periods');
    }
};
