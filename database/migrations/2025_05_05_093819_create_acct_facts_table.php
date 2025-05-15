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
        Schema::create('acct_facts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('coa_id');
            $table->unsignedBigInteger('record_id');
            $table->decimal('debit', 10, 2);
            $table->decimal('credit', 10, 2);
            $table->date('posted_date');
            $table->boolean('status');
            $table->timestamps();

            $table->foreign('coa_id')->on('chart_of_accounts')->references('id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('record_id')->on('employee_salary_periods')->references('id')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acct_facts');
    }
};
