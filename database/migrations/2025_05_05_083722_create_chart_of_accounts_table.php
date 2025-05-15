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
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_no')->unique();
            $table->string('account_name');
            $table->unsignedBigInteger('coa_category_id');
            $table->string('account_category_name');
            $table->enum('account_type', ['A', 'C', 'E', 'L', 'R', 'N']);
            $table->boolean('status');
            $table->timestamps();

            $table->foreign('coa_category_id')->on('chart_of_account_categories')->references('id')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chart_of_accounts');
    }
};
