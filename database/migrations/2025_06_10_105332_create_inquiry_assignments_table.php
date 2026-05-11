<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('inquiry_assignments', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('inquiry_id');
        $table->unsignedBigInteger('agency_id');
        $table->unsignedBigInteger('mcmc_staff_id');
        $table->text('notes')->nullable();
        $table->timestamp('assigned_at')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiry_assignments');
    }
};
