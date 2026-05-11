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
    Schema::create('inquiries', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('full_name');
        $table->string('phone_number');
        $table->string('email');
        $table->string('subject');
        $table->text('message');
        $table->string('source_type');
        $table->string('source_url')->nullable();
        $table->string('attachment')->nullable();
        $table->string('status')->default('Pending'); // 'Pending', 'Verified', etc.
        $table->json('history')->nullable(); // for status change tracking
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
