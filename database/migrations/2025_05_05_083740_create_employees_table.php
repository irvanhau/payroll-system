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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('coa_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->enum('gender', ['L', 'P']);
            $table->date('birth_date');
            $table->string('birth_place');
            $table->string('religion');
            $table->text('address');
            $table->string('npwp')->nullable();
            $table->string('phone_number');
            $table->string('occupation');
            $table->decimal('salary_amount', 10, 2)->default(0);
            $table->decimal('total_salary_allowance', 10, 2)->default(0);
            $table->decimal('total_salary_deduction', 10, 2)->default(0);
            $table->decimal('net_salary_amount', 10, 2)->default(0);
            $table->boolean('status')->default(1);
            $table->timestamps();

            $table->foreign('coa_id')->on('chart_of_accounts')->references('id')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
