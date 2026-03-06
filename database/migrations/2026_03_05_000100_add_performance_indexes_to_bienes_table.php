<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bienes', function (Blueprint $table) {
            $table->index('estado', 'bienes_estado_idx');
            $table->index('ubicacion', 'bienes_ubicacion_idx');
            $table->index('nombre', 'bienes_nombre_idx');
            $table->index('categoria', 'bienes_categoria_idx');
        });
    }

    public function down(): void
    {
        Schema::table('bienes', function (Blueprint $table) {
            $table->dropIndex('bienes_estado_idx');
            $table->dropIndex('bienes_ubicacion_idx');
            $table->dropIndex('bienes_nombre_idx');
            $table->dropIndex('bienes_categoria_idx');
        });
    }
};
