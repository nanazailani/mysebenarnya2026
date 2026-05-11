<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleAndFirstLoginToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('public_user');
            }
            if (! Schema::hasColumn('users', 'is_first_login')) {
                $table->boolean('is_first_login')->default(false);
            }
            if (! Schema::hasColumn('users', 'verification_code')) {
                $table->string('verification_code')->nullable();
            }
            if (! Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
            if (Schema::hasColumn('users', 'is_first_login')) {
                $table->dropColumn('is_first_login');
            }
            if (Schema::hasColumn('users', 'verification_code')) {
                $table->dropColumn('verification_code');
            }
            if (Schema::hasColumn('users', 'email_verified_at')) {
                $table->dropColumn('email_verified_at');
            }
        });
    }
}
