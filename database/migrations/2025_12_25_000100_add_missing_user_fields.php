<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('user');
            }
            if (!Schema::hasColumn('users', 'security_color_answer')) {
                $table->string('security_color_answer')->nullable();
            }
            if (!Schema::hasColumn('users', 'security_animal_answer')) {
                $table->string('security_animal_answer')->nullable();
            }
            if (!Schema::hasColumn('users', 'security_padre_answer')) {
                $table->string('security_padre_answer')->nullable();
            }
            if (!Schema::hasColumn('users', 'login_attempts')) {
                $table->integer('login_attempts')->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
            if (Schema::hasColumn('users', 'security_color_answer')) {
                $table->dropColumn('security_color_answer');
            }
            if (Schema::hasColumn('users', 'security_animal_answer')) {
                $table->dropColumn('security_animal_answer');
            }
            if (Schema::hasColumn('users', 'security_padre_answer')) {
                $table->dropColumn('security_padre_answer');
            }
            if (Schema::hasColumn('users', 'login_attempts')) {
                $table->dropColumn('login_attempts');
            }
        });
    }
};
