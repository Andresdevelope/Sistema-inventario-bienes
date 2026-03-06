<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 30)->unique();
            $table->string('estado', 10)->default('activo');
            $table->timestamps();
        });

        $categoriasExistentes = DB::table('bienes')
            ->select('categoria')
            ->whereNotNull('categoria')
            ->whereRaw("TRIM(categoria) <> ''")
            ->distinct()
            ->pluck('categoria');

        foreach ($categoriasExistentes as $categoria) {
            $nombre = trim((string) $categoria);

            if ($nombre === '') {
                continue;
            }

            DB::table('categorias')->updateOrInsert(
                ['nombre' => $nombre],
                [
                    'estado' => 'activo',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
