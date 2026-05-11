<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add a nullable "contact" column. 
            // You can change the type/length as needed (e.g. string(100))
            $table->string('contact')->nullable()->after('email');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the column if rolling back
            $table->dropColumn('contact');
        });
    }
};
