<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $titulo }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #0f172a; font-size: 12px; }
        .header { margin-bottom: 12px; border-bottom: 1px solid #cbd5e1; padding-bottom: 10px; }
        .header-table { width: 100%; border: none; margin: 0; }
        .header-table td { border: none; padding: 0; vertical-align: middle; }
        .logo-wrap { width: 64px; }
        .logo { width: 56px; height: 56px; object-fit: contain; }
        .institution { font-size: 11px; color: #475569; margin: 0; }
        h1 { font-size: 18px; margin-bottom: 4px; }
        .meta { color: #475569; margin-bottom: 16px; }
        .resume { margin-bottom: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #cbd5e1; padding: 6px 8px; text-align: left; }
        th { background: #e2e8f0; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    @php
        $logoPath = public_path('logo-institucion.jpg');
        $logoDataUri = null;

        if (is_file($logoPath)) {
            $logoMime = mime_content_type($logoPath) ?: 'image/jpeg';
            $logoDataUri = 'data:' . $logoMime . ';base64,' . base64_encode(file_get_contents($logoPath));
        }
    @endphp

    <div class="header">
        <table class="header-table">
            <tr>
                <td class="logo-wrap">
                    @if($logoDataUri)
                        <img src="{{ $logoDataUri }}" alt="Logo institucional" class="logo">
                    @endif
                </td>
                <td>
                    <p class="institution"><strong>Sistema Inventario de Bienes</strong></p>
                    <p class="institution">Reporte institucional</p>
                </td>
            </tr>
        </table>
    </div>

    <h1>{{ $titulo }}</h1>
    <p class="meta">Generado: {{ $fechaGeneracion }} | Sistema Inventario de Bienes</p>

    <div class="resume">
        <strong>Total de bienes en reporte:</strong> {{ $totalBienes }}
        @if ($filtroCategoria)
            <br><strong>Categoría filtrada:</strong> {{ $filtroCategoria }}
        @endif
    </div>

    <h2 style="font-size:14px; margin: 8px 0 4px;">Resumen agrupado por categoría</h2>
    <table>
        <thead>
            <tr>
                <th>Categoría</th>
                <th class="text-right">Cantidad</th>
            </tr>
        </thead>
        <tbody>
            @forelse($resumenCategorias as $row)
                <tr>
                    <td>{{ $row->categoria_nombre }}</td>
                    <td class="text-right">{{ $row->total }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2">No hay datos para mostrar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h2 style="font-size:14px; margin: 14px 0 4px;">Detalle de bienes</h2>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Código</th>
                <th>Categoría</th>
                <th>Estado</th>
                <th>Ubicación</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bienes as $bien)
                @php($estado = strtolower(trim((string) $bien->estado)))
                @php($estadoLabel = $estado === 'de_baja' ? 'Dado de baja' : ucfirst($estado))
                <tr>
                    <td>{{ $bien->nombre }}</td>
                    <td>{{ $bien->codigo }}</td>
                    <td>{{ $bien->categoria ?? 'Sin categoría' }}</td>
                    <td>{{ $estadoLabel }}</td>
                    <td>{{ $bien->ubicacion ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No hay bienes para exportar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
