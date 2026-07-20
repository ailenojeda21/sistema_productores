<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class StaffProducerController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view-producers');

        $all = trim((string) $request->get('all', ''));
        $dni = trim((string) $request->get('dni', ''));
        $name = trim((string) $request->get('name', ''));
        $distrito = trim((string) $request->get('distrito', ''));
        $variedad = trim((string) $request->get('variedad', ''));
        $tipo = trim((string) $request->get('tipo', ''));
        $rut = trim((string) $request->get('rut', ''));

        $producers = User::query()
            ->select('users.id', 'users.name', 'users.dni', 'users.email')
            ->distinct()

            ->when($all !== '1' && $dni !== '', fn ($q) => $q->where('users.dni', 'like', "%{$dni}%"))

            ->when($all !== '1' && $name !== '', fn ($q) => $q->where('users.name', 'like', "%{$name}%"))

            ->when($all !== '1' && $distrito !== '', function ($q) use ($distrito) {
                $normalized = strtolower(str_replace(' ', '-', trim($distrito)));
                $search = str_replace('-', '', $normalized);

                $q->whereHas('propiedades', function ($sub) use ($search) {
                    $sub->whereRaw(
                        "LOWER(REPLACE(REPLACE(distrito, '-', ''), ' ', '')) LIKE ?",
                        ["%{$search}%"]
                    );
                });
            })

            ->when($all !== '1' && $variedad !== '', function ($q) use ($variedad) {
                $q->whereHas('propiedades.cultivos', fn ($sub) => $sub->where('variedad', 'like', "%{$variedad}%"));
            })

            ->when($all !== '1' && $tipo !== '', function ($q) use ($tipo) {
                $q->whereHas('propiedades.cultivos', fn ($sub) => $sub->where('tipo', 'like', "%{$tipo}%"));
            })

            ->when($all !== '1' && $rut !== '', function ($q) use ($rut) {
                $search = preg_replace('/\D/', '', $rut);

                $q->whereHas('propiedades', function ($sub) use ($search) {
                    $sub->where('rut', 1)
                        ->where('rut_valor', 'like', "%{$search}%");
                });
            })

            ->orderBy('users.id', 'desc')
            ->paginate(10)
            ->withQueryString();

        $producers->getCollection()->transform(fn ($u) => [
            'id' => $u->id,
            'name' => $u->name,
            'dni' => $u->dni,
            'email' => $u->email,
        ]);

        $user = $request->user();

        $filters = [
            'all' => $all,
            'dni' => $dni,
            'name' => $name,
            'distrito' => $distrito,
            'variedad' => $variedad,
            'tipo' => $tipo,
            'rut' => $rut,
        ];

        if ($this->isApiRequest($request)) {
            return response()->json([
                'filters' => $filters,
                'producers' => $producers,
            ]);
        }

        return inertia('Staff/Producers/Index', [
            'user' => $user,
            'filters' => $filters,
            'producers' => $producers,
        ]);
    }

    public function show(Request $request, $id)
    {
        $this->authorize('view-producers');

        $producer = User::with([
            'propiedades.cultivos',
            'propiedades.maquinaria',
            'comercializacion',
        ])->findOrFail($id);

        $user = $request->user();

        // Preparar propiedades con dirección completa
        $propiedades = $producer->propiedades->map(function ($prop) {
            return [
                'id' => $prop->id,
                'direccion' => $prop->direccion_completa,
                'hectareas' => $prop->hectareas,
                'tipo_tenencia' => $prop->tipo_tenencia,
                'especificar_tenencia' => $prop->especificar_tenencia,
                'derecho_riego' => $prop->derecho_riego,
                'tipo_derecho_riego' => $prop->tipo_derecho_riego,
                'malla' => $prop->malla,
                'hectareas_malla' => $prop->hectareas_malla,
                'cierre_perimetral' => $prop->cierre_perimetral,
                'rut' => $prop->rut,
                'rut_valor' => $prop->rut_valor,
                'rut_archivo_url' => $prop->rut_archivo ? route('staff.propiedades.rut', $prop) : null,
                'lat' => $prop->lat,
                'lng' => $prop->lng,
            ];
        });

        // Recolectar cultivos de todas las propiedades
        $cultivos = [];
        foreach ($producer->propiedades as $prop) {
            foreach ($prop->cultivos as $cult) {
                $cultivos[] = [
                    'id' => $cult->id,
                    'nombre' => $cult->variedad,
                    'tipo' => $cult->tipo,
                    'hectareas' => $cult->hectareas,
                    'manejo_cultivo' => $cult->manejo_cultivo,
                    'tecnologia_riego' => $cult->tecnologia_riego,
                    'propiedad' => [
                        'direccion' => $prop->direccion_completa,
                    ],
                ];
            }
        }

        // Recolectar maquinarias de todas las propiedades
        $maquinarias = [];

        foreach ($producer->propiedades as $prop) {
            if ($prop->maquinaria) {
                $maq = $prop->maquinaria;

                $maquinarias[] = [
                    'id' => $maq->id,
                    'tractor' => $maq->tractor,
                    'modelo_tractor' => $maq->modelo_tractor,
                    'implementos' => $maq->implementos_activos,
                    'implementos_flags' => $maq->implementos_flags,
                    'propiedad' => [
                        'direccion' => $prop->direccion_completa,
                    ],
                ];
            }
        }

        // Datos de comercialización
        $comercio = $producer->comercializacion ? [
            'infraestructura_empaque' => $producer->comercializacion->infraestructura_empaque,
            'vende_en_finca' => $producer->comercializacion->vende_en_finca,
            'mercados' => $producer->comercializacion->mercados,
            'cooperativas' => $producer->comercializacion->cooperativas,
        ] : null;

        // Calcular stats
        $stats = [
            'propiedades' => $propiedades->count(),
            'cultivos' => count($cultivos),
            'maquinarias' => count($maquinarias),
            'hectareas' => $propiedades->sum('hectareas'),
        ];

        $responseData = [
            'producer' => [
                'id' => $producer->id,
                'name' => $producer->name,
                'dni' => $producer->dni,
                'email' => $producer->email,
                'telefono' => $producer->telefono,
                'cooperativas' => $producer->cooperativas,
            ],
            'propiedades' => $propiedades,
            'cultivos' => $cultivos,
            'maquinarias' => $maquinarias,
            'comercio' => $comercio,
            'stats' => $stats,
        ];

        if ($this->isApiRequest($request)) {
            return response()->json($responseData);
        }

        return inertia('Staff/Producers/Show', array_merge(
            ['authUser' => $user],
            $responseData
        ));
    }

    public function export(Request $request)
    {
        $this->authorize('export-producers');

        $all = trim((string) $request->get('all', ''));
        $dni = trim((string) $request->get('dni', ''));
        $name = trim((string) $request->get('name', ''));
        $distrito = trim((string) $request->get('distrito', ''));
        $variedad = trim((string) $request->get('variedad', ''));
        $tipo = trim((string) $request->get('tipo', ''));
        $rut = trim((string) $request->get('rut', ''));

        $producers = User::with([
            'comercializacion',
            'propiedades.maquinaria',
            'propiedades.cultivos',
        ])
            ->distinct()

            ->when($all !== '1' && $dni !== '', fn ($q) => $q->where('users.dni', 'like', "%{$dni}%"))

            ->when($all !== '1' && $name !== '', fn ($q) => $q->where('users.name', 'like', "%{$name}%"))

            ->when($all !== '1' && $distrito !== '', function ($q) use ($distrito) {
                $normalized = strtolower(str_replace(' ', '-', trim($distrito)));
                $search = str_replace('-', '', $normalized);

                $q->whereHas('propiedades', function ($sub) use ($search) {
                    $sub->whereRaw(
                        "LOWER(REPLACE(REPLACE(distrito, '-', ''), ' ', '')) LIKE ?",
                        ["%{$search}%"]
                    );
                });
            })

            ->when($all !== '1' && $variedad !== '', function ($q) use ($variedad) {
                $q->whereHas('propiedades.cultivos', fn ($sub) => $sub->where('variedad', 'like', "%{$variedad}%"));
            })

            ->when($all !== '1' && $tipo !== '', function ($q) use ($tipo) {
                $q->whereHas('propiedades.cultivos', fn ($sub) => $sub->where('tipo', 'like', "%{$tipo}%"));
            })

            ->when($all !== '1' && $rut !== '', function ($q) use ($rut) {
                $search = preg_replace('/\D/', '', $rut);

                $q->whereHas('propiedades', function ($sub) use ($search) {
                    $sub->where('rut', 1)
                        ->where('rut_valor', 'like', "%{$search}%");
                });
            })

            ->get();

        $headers = [
            // User
            'Nombre', 'Email', 'DNI', 'Teléfono', 'Dirección',
            // Comercio
            'Infraestructura de empaque', 'Vende en finca', 'Mercados', 'Cooperativas',
            // Propiedad
            'Calle', 'Numeración', 'Dirección completa', 'Distrito', 'Hectáreas',
            'Derecho de riego', 'Tipo derecho de riego', 'Posee RUT', 'Valor del RUT',
            'Latitud', 'Longitud', 'Hectáreas con malla', 'Cierre perimetral', 'Posee malla',
            'Tipo de tenencia', 'Especificar tenencia',
            // Maquinaria
            'Tractor', 'Modelo tractor', 'Arado', 'Rastra', 'Niveleta común', 'Niveleta láser',
            'Cincel/Cultivadora', 'Desmalezadora', 'Pulverizadora', 'Mochila pulverizadora',
            'Cosechadora', 'Enfardadora', 'Retroexcavadora', 'Carro/Carretón', 'Múltiple',
            // Cultivo
            'Tipo', 'Variedad', 'Estación', 'Hectáreas', 'Manejo del cultivo', 'Tecnología de riego',
        ];

        $searchValue = $variedad ?: $tipo ?: $distrito;
        $searchType = $all === '1' ? 'all' : ($variedad ? 'variedad' : ($tipo ? 'tipo' : ($distrito ? 'distrito' : null)));

        $titulo = 'Listado de Productores';
        if ($searchType === 'all') {
            $titulo = 'Todos los Productores';
        } elseif ($searchType === 'distrito') {
            $titulo = 'Productores del Distrito ' . $distrito;
        } elseif ($searchType === 'variedad') {
            $titulo = 'Productores que cultivan ' . $variedad;
        } elseif ($searchType === 'tipo') {
            $titulo = 'Productores de tipo ' . $tipo;
        }

        $fechaExport = date('d/m/Y H:i') . ' hs';
        $dateStr = date('Y-m-d');

        if ($searchType === 'all') {
            $filename = 'productores_completo_' . $dateStr . '.xlsx';
        } elseif ($searchType && $searchValue) {
            $filename = 'productores_' . strtolower(str_replace(' ', '_', $searchValue)) . '_' . $dateStr . '.xlsx';
        } else {
            $filename = 'productores_todos_' . $dateStr . '.xlsx';
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Productores');

        $lastCol = $this->colLetter(count($headers));
        $headerRow = 4;

        // Título
        $sheet->setCellValue('A1', $titulo);
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        // Fecha
        $sheet->setCellValue('A2', "Fecha de exportación: {$fechaExport}");
        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->getStyle('A2')->getFont()->setSize(10)->getColor()->setARGB('FF64748B');

        // Headers fila 4 con estilo
        foreach ($headers as $i => $header) {
            $colLetter = $this->colLetter($i + 1);
            $sheet->setCellValue("{$colLetter}{$headerRow}", $header);
            $sheet->getStyle("{$colLetter}{$headerRow}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E40AF']],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            ]);
        }

        // Autofiltro
        $sheet->setAutoFilter("A{$headerRow}:{$lastCol}{$headerRow}");

        // Congelar encabezado
        $sheet->freezePane("A" . ($headerRow + 1));

        // Datos desde fila 5
        $rowNum = 5;
        foreach ($producers as $producer) {
            $comercio = $producer->comercializacion;

            // Resolver etiquetas de mercados y cooperativas
            $mercadosLabels = $comercio
                ? collect($comercio->mercados ?? [])
                    ->map(fn($k) => \App\Models\Comercio::MERCADOS[$k] ?? $k)
                    ->implode(', ')
                : '';

            $cooperativasLabels = $comercio
                ? collect($comercio->cooperativas ?? [])
                    ->map(fn($k) => \App\Models\Comercio::COOPERATIVAS[$k] ?? $k)
                    ->implode(', ')
                : '';

            $propiedades = $producer->propiedades;

            if ($propiedades->isEmpty()) {
                // Productor sin propiedades: una fila con datos básicos
                $this->writeExcelRow($sheet, $rowNum, [
                    $producer->name, $producer->email, $producer->dni ?? '', $producer->telefono ?? '', $producer->direccion ?? '',
                    $comercio ? ($comercio->infraestructura_empaque ? 'Sí' : 'No') : '',
                    $comercio ? ($comercio->vende_en_finca ? 'Sí' : 'No') : '',
                    $mercadosLabels, $cooperativasLabels,
                    '', '', '', '', '',
                    '', '', '', '', '', '', '', '', '',
                    '', '',
                    '', '', '', '', '', '', '', '', '', '',
                    '', '', '', '', '',
                    '', '', '', '', '', '',
                ]);
                $rowNum++;
            } else {
                foreach ($propiedades as $prop) {
                    $maquinaria = $prop->maquinaria;
                    $cultivos = $prop->cultivos;

                    $propData = [
                        $prop->calle ?? '',
                        $prop->numeracion ?? '',
                        $prop->direccion_completa,
                        $prop->distrito_label,
                        $prop->hectareas,
                        $prop->derecho_riego ? 'Sí' : 'No',
                        $prop->tipo_derecho_riego_label,
                        $prop->rut ? 'Sí' : 'No',
                        $prop->rut_valor ?? '',
                        $prop->lat,
                        $prop->lng,
                        $prop->hectareas_malla ?? '0.00',
                        $prop->cierre_perimetral ? 'Sí' : 'No',
                        $prop->malla ? 'Sí' : 'No',
                        $prop->tipo_tenencia_label,
                        $prop->especificar_tenencia ?? '',
                    ];

                    $maqData = $maquinaria ? [
                        $maquinaria->tractor ? 'Sí' : 'No',
                        $maquinaria->modelo_tractor ?? '',
                        $maquinaria->arado ? 'Sí' : 'No',
                        $maquinaria->rastra ? 'Sí' : 'No',
                        $maquinaria->niveleta_comun ? 'Sí' : 'No',
                        $maquinaria->niveleta_laser ? 'Sí' : 'No',
                        $maquinaria->cincel_cultivadora ? 'Sí' : 'No',
                        $maquinaria->desmalezadora ? 'Sí' : 'No',
                        $maquinaria->pulverizadora_tractor ? 'Sí' : 'No',
                        $maquinaria->mochila_pulverizadora ? 'Sí' : 'No',
                        $maquinaria->cosechadora ? 'Sí' : 'No',
                        $maquinaria->enfardadora ? 'Sí' : 'No',
                        $maquinaria->retroexcavadora ? 'Sí' : 'No',
                        $maquinaria->carro_carreton ? 'Sí' : 'No',
                        $maquinaria->multiple ? 'Sí' : 'No',
                    ] : array_fill(0, 15, '');

                    $userData = [
                        $producer->name, $producer->email, $producer->dni ?? '', $producer->telefono ?? '', $producer->direccion ?? '',
                        $comercio ? ($comercio->infraestructura_empaque ? 'Sí' : 'No') : '',
                        $comercio ? ($comercio->vende_en_finca ? 'Sí' : 'No') : '',
                        $mercadosLabels, $cooperativasLabels,
                    ];

                    if ($cultivos->isNotEmpty()) {
                        foreach ($cultivos as $cult) {
                            $cultData = [
                                $cult->tipo ?? '',
                                $cult->variedad ?? '',
                                $cult->estacion ?? '',
                                $cult->hectareas,
                                $cult->manejo_label,
                                \App\Models\Cultivo::TECNOLOGIA_RIEGO[$cult->tecnologia_riego] ?? $cult->tecnologia_riego ?? '',
                            ];

                            $this->writeExcelRow($sheet, $rowNum, array_merge($userData, $propData, $maqData, $cultData));
                            $rowNum++;
                        }
                    } else {
                        // Propiedad sin cultivos: una fila con datos de propiedad pero celdas de cultivo vacías
                        $emptyCult = ['', '', '', '', '', ''];
                        $this->writeExcelRow($sheet, $rowNum, array_merge($userData, $propData, $maqData, $emptyCult));
                        $rowNum++;
                    }
                }
            }
        }

        // Auto-size columns
        for ($i = 1; $i <= count($headers); $i++) {
            $sheet->getColumnDimension($this->colLetter($i))->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();

        return response($content, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function writeExcelRow($sheet, int $row, array $data): void
    {
        foreach ($data as $i => $value) {
            $colLetter = $this->colLetter($i + 1);
            $cell = $sheet->getCell("{$colLetter}{$row}");
            if (is_float($value) || is_int($value)) {
                $cell->setValueExplicit($value, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
            } elseif (is_null($value)) {
                $cell->setValue('');
            } else {
                $cell->setValue($value);
            }
        }
    }

    private function colLetter(int $index): string
    {
        $letter = '';
        while ($index > 0) {
            $index--;
            $letter = chr(65 + ($index % 26)) . $letter;
            $index = intdiv($index, 26);
        }
        return $letter;
    }
}
