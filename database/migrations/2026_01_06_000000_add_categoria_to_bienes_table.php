<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bienes', function (Blueprint $table) {
            if (!Schema::hasColumn('bienes', 'categoria')) {
                $table->string('categoria', 100)->nullable()->after('descripcion');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bienes', function (Blueprint $table) {
            if (Schema::hasColumn('bienes', 'categoria')) {
                $table->dropColumn('categoria');
            }
        });
    }
};
