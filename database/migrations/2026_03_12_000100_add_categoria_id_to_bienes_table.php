<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bienes', function (Blueprint $table) {
            if (!Schema::hasColumn('bienes', 'categoria_id')) {
                $table->foreignId('categoria_id')
                    ->nullable()
                    ->after('categoria')
                    ->constrained('categorias')
                    ->nullOnDelete();

                $table->index('categoria_id', 'bienes_categoria_id_idx');
            }
        });

        $normalizeName = static function (?string $value): string {
            $clean = trim(strip_tags((string) $value));
            $clean = preg_replace('/\s+/u', ' ', $clean) ?? $clean;

            return mb_convert_case($clean, MB_CASE_TITLE, 'UTF-8');
        };

        $bienes = DB::table('bienes')
            ->select('id', 'categoria')
            ->whereNotNull('categoria')
            ->whereRaw("TRIM(categoria) <> ''")
            ->get();

        foreach ($bienes as $bien) {
            $nombre = $normalizeName($bien->categoria);

            if ($nombre === '') {
                continue;
            }

            $categoriaId = DB::table('categorias')
                ->where('nombre', $nombre)
                ->value('id');

            if (! $categoriaId) {
                $categoriaId = DB::table('categorias')->insertGetId([
                    'nombre' => $nombre,
                    'estado' => 'activo',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('bienes')
                ->where('id', $bien->id)
                ->update([
                    'categoria' => $nombre,
                    'categoria_id' => $categoriaId,
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('bienes', function (Blueprint $table) {
            if (Schema::hasColumn('bienes', 'categoria_id')) {
                $table->dropForeign(['categoria_id']);
                $table->dropIndex('bienes_categoria_id_idx');
                $table->dropColumn('categoria_id');
            }
        });
    }
};
