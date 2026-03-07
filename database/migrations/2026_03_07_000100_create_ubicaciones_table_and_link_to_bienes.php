<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ubicaciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            $table->string('estado', 10)->default('activo');
            $table->timestamps();
        });

        Schema::table('bienes', function (Blueprint $table) {
            if (!Schema::hasColumn('bienes', 'ubicacion_id')) {
                $table->foreignId('ubicacion_id')
                    ->nullable()
                    ->after('ubicacion')
                    ->constrained('ubicaciones')
                    ->nullOnDelete();

                $table->index('ubicacion_id', 'bienes_ubicacion_id_idx');
            }
        });

        $normalizeName = static function (?string $value): string {
            $clean = trim(strip_tags((string) $value));
            $clean = preg_replace('/\s+/u', ' ', $clean) ?? $clean;

            return mb_convert_case($clean, MB_CASE_TITLE, 'UTF-8');
        };

        $ubicacionesExistentes = DB::table('bienes')
            ->select('ubicacion')
            ->whereNotNull('ubicacion')
            ->whereRaw("TRIM(ubicacion) <> ''")
            ->distinct()
            ->pluck('ubicacion');

        foreach ($ubicacionesExistentes as $ubicacion) {
            $nombre = $normalizeName($ubicacion);

            if ($nombre === '') {
                continue;
            }

            DB::table('ubicaciones')->updateOrInsert(
                ['nombre' => $nombre],
                [
                    'estado' => 'activo',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $bienes = DB::table('bienes')
            ->select('id', 'ubicacion')
            ->whereNotNull('ubicacion')
            ->whereRaw("TRIM(ubicacion) <> ''")
            ->get();

        foreach ($bienes as $bien) {
            $nombre = $normalizeName($bien->ubicacion);

            if ($nombre === '') {
                continue;
            }

            $ubicacionId = DB::table('ubicaciones')
                ->where('nombre', $nombre)
                ->value('id');

            if (! $ubicacionId) {
                continue;
            }

            DB::table('bienes')
                ->where('id', $bien->id)
                ->update([
                    'ubicacion' => $nombre,
                    'ubicacion_id' => $ubicacionId,
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('bienes', function (Blueprint $table) {
            if (Schema::hasColumn('bienes', 'ubicacion_id')) {
                $table->dropForeign(['ubicacion_id']);
                $table->dropIndex('bienes_ubicacion_id_idx');
                $table->dropColumn('ubicacion_id');
            }
        });

        Schema::dropIfExists('ubicaciones');
    }
};