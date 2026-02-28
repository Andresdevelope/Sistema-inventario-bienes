<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('bienes', 'valor')) {
            Schema::table('bienes', function (Blueprint $table) {
                $table->dropColumn('valor');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('bienes', 'valor')) {
            Schema::table('bienes', function (Blueprint $table) {
                $table->decimal('valor', 12, 2)->nullable();
            });
        }
    }
};
