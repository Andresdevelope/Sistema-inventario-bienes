<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bienes', function (Blueprint $table) {
            if (Schema::hasColumn('bienes', 'categoria')) {
                $table->dropIndex('bienes_categoria_idx');
                $table->dropColumn('categoria');
            }

            if (Schema::hasColumn('bienes', 'ubicacion')) {
                $table->dropIndex('bienes_ubicacion_idx');
                $table->dropColumn('ubicacion');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bienes', function (Blueprint $table) {
            if (!Schema::hasColumn('bienes', 'categoria')) {
                $table->string('categoria', 100)->nullable()->after('descripcion');
            }

            if (!Schema::hasColumn('bienes', 'ubicacion')) {
                $table->string('ubicacion')->nullable()->after('categoria_id');
            }
        });

        Schema::table('bienes', function (Blueprint $table) {
            if (Schema::hasColumn('bienes', 'categoria')) {
                $table->index('categoria', 'bienes_categoria_idx');
            }

            if (Schema::hasColumn('bienes', 'ubicacion')) {
                $table->index('ubicacion', 'bienes_ubicacion_idx');
            }
        });
    }
};
