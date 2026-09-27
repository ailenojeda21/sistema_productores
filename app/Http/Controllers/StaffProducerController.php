<?php

namespace App\Http\Controllers;

use App\Models\Cultivo;
use App\Models\User;
use App\Services\CertificateService;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class StaffProducerController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view-producers');

        $dni = trim((string) $request->get('dni', ''));
        $name = trim((string) $request->get('name', ''));
        $distrito = trim((string) $request->get('distrito', ''));
        $variedad = trim((string) $request->get('variedad', ''));
        $tipo = trim((string) $request->get('tipo', ''));
        $rut = trim((string) $request->get('rut', ''));

        $producers = User::query()
            ->select('users.id', 'users.name', 'users.dni', 'users.email')
            ->distinct()
            ->when($dni !== '', fn ($q) => $q->where('users.dni', 'like', "%{$dni}%"))
            ->when($name !== '', fn ($q) => $q->where('users.name', 'like', "%{$name}%"))
            ->when($distrito !== '', function ($q) use ($distrito) {
                $q->whereHas('propiedades', fn ($sub) => $this->filtrarPorDistrito($sub, $distrito));
            })

            ->when($variedad !== '', function ($q) use ($variedad) {
                $q->whereHas('propiedades.cultivos', fn ($sub) => $sub->where('variedad', 'like', "%{$variedad}%"));
            })

            ->when($tipo !== '', function ($q) use ($tipo) {
                $q->whereHas('propiedades.cultivos', fn ($sub) => $sub->where('tipo', 'like', "%{$tipo}%"));
            })

            ->when($rut !== '', function ($q) use ($rut) {
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
            'folioInf' => sprintf(
                'INF-%s-%s',
                str_pad((string) $producer->id, 6, '0', STR_PAD_LEFT),
                now()->format('Ymd')
            ),
        ];

        if (! $this->isApiRequest($request)) {
            $verificacionUrl = CertificateService::verificationUrl(
                CertificateService::TIPO_INFORME,
                $producer->id
            );
            $responseData['verificationUrl'] = $verificacionUrl;
            $responseData['verificationQr'] = CertificateService::qrDataUri($verificacionUrl);
        }

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

        $dni = trim((string) $request->get('dni', ''));
        $name = trim((string) $request->get('name', ''));
        $distrito = trim((string) $request->get('distrito', ''));
        $variedad = trim((string) $request->get('variedad', ''));
        $tipo = trim((string) $request->get('tipo', ''));
        $rut = trim((string) $request->get('rut', ''));

        $producers = User::with([
            'propiedades.cultivos',
        ])
            ->distinct()

            ->when($dni !== '', fn ($q) => $q->where('users.dni', 'like', "%{$dni}%"))

            ->when($name !== '', fn ($q) => $q->where('users.name', 'like', "%{$name}%"))

            ->when($distrito !== '', function ($q) use ($distrito) {
                $q->whereHas('propiedades', fn ($sub) => $this->filtrarPorDistrito($sub, $distrito));
            })

            ->when($variedad !== '', function ($q) use ($variedad) {
                $q->whereHas('propiedades.cultivos', fn ($sub) => $sub->where('variedad', 'like', "%{$variedad}%"));
            })

            ->when($tipo !== '', function ($q) use ($tipo) {
                $q->whereHas('propiedades.cultivos', fn ($sub) => $sub->where('tipo', 'like', "%{$tipo}%"));
            })

            ->when($rut !== '', function ($q) use ($rut) {
                $search = preg_replace('/\D/', '', $rut);

                $q->whereHas('propiedades', function ($sub) use ($search) {
                    $sub->where('rut', 1)
                        ->where('rut_valor', 'like', "%{$search}%");
                });
            })

            ->get();

        // `distrito` y `rut` son filtros a nivel propiedad: el archivo lleva
        // perfil + las propiedades que coinciden. `variedad` y `tipo` son
        // filtros a nivel cultivo: ademas se trae la propiedad que lo contiene
        // y el modulo cultivo.
        $filtraCultivo = $variedad !== '' || $tipo !== '';

        // Las relaciones se recargan con la MISMA restriccion que usa el
        // `whereHas` de arriba, para que el archivo no arrastre propiedades ni
        // cultivos que no corresponden al filtro activo. La normalizacion de
        // `distrito` se replica aqui a proposito: cualquier divergencia
        // haria perder filas que la consulta si habria|matchado.
        $producers->load([
            'propiedades' => function ($q) use ($distrito, $rut, $variedad, $tipo) {
                if ($distrito !== '') {
                    $this->filtrarPorDistrito($q, $distrito);
                }

                if ($rut !== '') {
                    $q->where('rut', 1)
                        ->where('rut_valor', 'like', '%'.preg_replace('/\D/', '', $rut).'%');
                }

                if ($variedad !== '' || $tipo !== '') {
                    $q->whereHas('cultivos', function ($sub) use ($variedad, $tipo) {
                        $this->aplicarFiltroCultivo($sub, $variedad, $tipo);
                    });
                }
            },

            // Sin filtro de cultivo se cargan todos, para el modulo completo;
            // con filtro, solo los cultivos que coinciden.
            'propiedades.cultivos' => function ($q) use ($variedad, $tipo) {
                $this->aplicarFiltroCultivo($q, $variedad, $tipo);
            },
        ]);

        $headers = [
            // Modulo perfil. `Direccion Productor` se renombra para
            // desambiguarlo de `Direccion completa`, que es de la propiedad.
            'ID', 'Nombre', 'Email', 'DNI', 'Teléfono', 'Dirección Productor',
            // Modulo propiedad. `Calle` y `Numeracion` no se exportan: quedan
            // absorbidas por `Direccion completa`.
            'Dirección Propiedad', 'Distrito', 'Hectáreas',
            'Derecho de riego', 'Tipo derecho de riego', 'Posee RUT', 'Valor del RUT',
            'Latitud', 'Longitud', 'Hectáreas con malla', 'Cierre perimetral', 'Posee malla',
            'Tipo de tenencia', 'Especificar tenencia',
        ];

        if ($filtraCultivo) {
            // Modulo cultivo: solo tiene sentido cuando se filtro por el.
            $headers[] = 'Tipo';
            $headers[] = 'Variedad';
            $headers[] = 'Estación';
            $headers[] = 'Hectáreas';
            $headers[] = 'Manejo del cultivo';
            $headers[] = 'Tecnología de riego';
        }

        $searchValue = $variedad ?: $tipo ?: $distrito;
        $searchType = $variedad ? 'variedad' : ($tipo ? 'tipo' : ($distrito ? 'distrito' : null));

        $titulo = 'Listado de Productores';
        if ($searchType === 'distrito') {
            $titulo = 'Productores del Distrito '.$distrito;
        } elseif ($searchType === 'variedad') {
            $titulo = 'Productores que cultivan '.$variedad;
        } elseif ($searchType === 'tipo') {
            $titulo = 'Productores de tipo '.$tipo;
        }

        $fechaExport = date('d/m/Y H:i').' hs';
        $dateStr = date('Y-m-d');

        if ($searchType && $searchValue) {
            $filename = 'productores_'.strtolower(str_replace(' ', '_', $searchValue)).'_'.$dateStr.'.xlsx';
        } else {
            $filename = 'productores_todos_'.$dateStr.'.xlsx';
        }

        $spreadsheet = new Spreadsheet;
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
        $sheet->freezePane('A'.($headerRow + 1));

        // Datos desde fila 5
        $rowNum = 5;
        foreach ($producers as $producer) {
            $perfil = [
                $producer->id,
                $producer->name,
                $producer->email,
                $producer->dni ?? '',
                $producer->telefono ?? '',
                $producer->direccion ?? '',
            ];

            $propiedades = $producer->propiedades;

            if ($propiedades->isEmpty()) {
                // El productor no tiene propiedades que mostrar: una fila solo
                // con el perfil completo.
                $this->writeExcelRow($sheet, $rowNum, $perfil);
                $rowNum++;

                continue;
            }

            foreach ($propiedades as $prop) {
                $propData = [
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

                if (! $filtraCultivo) {
                    // Filtro de propiedad (distrito, rut) o sin filtro de
                    // cultivo: una fila por propiedad, sin columnas de cultivo.
                    $this->writeExcelRow($sheet, $rowNum, array_merge($perfil, $propData));
                    $rowNum++;

                    continue;
                }

                // Filtro de cultivo: la propiedad solo aparece si tiene un
                // cultivo que coincida, y solo se emiten esos cultivos.
                $cultivosCoincidentes = $prop->cultivos->filter(
                    fn ($cult) => $this->cultivoCoincide($cult, $variedad, $tipo)
                );

                foreach ($cultivosCoincidentes as $cult) {
                    $cultData = [
                        $cult->tipo ?? '',
                        $cult->variedad ?? '',
                        $cult->estacion ?? '',
                        $cult->hectareas,
                        $cult->manejo_label,
                        Cultivo::TECNOLOGIA_RIEGO[$cult->tecnologia_riego] ?? $cult->tecnologia_riego ?? '',
                    ];

                    $this->writeExcelRow($sheet, $rowNum, array_merge($perfil, $propData, $cultData));
                    $rowNum++;
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
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    /**
     * Aplica el filtro de `variedad` / `tipo` sobre una query de cultivos.
     *
     * Se usa en dos lugares: el `whereHas` que elige los productores y el
     * eager load restringido que elige que propiedades y cultivos se escriben
     * en el archivo. Ambos deben usar la misma prediccion.
     */
    private function aplicarFiltroCultivo($query, string $variedad, string $tipo): void
    {
        $query
            ->when($variedad !== '', fn ($q) => $q->where('variedad', 'like', "%{$variedad}%"))
            ->when($tipo !== '', fn ($q) => $q->where('tipo', 'like', "%{$tipo}%"));
    }

    /**
     * Normaliza el texto buscado por distrito para que sea comparable con la
     * columna `distrito`, que se guarda en formato slug (`la-pega`).
     *
     * Se quitan guiones y cualquier tipo de espacio y se pasa a minusculas, de
     * modo que `La Pega`, `la-pega`, `LaPega` y `  LA   PEGA  ` colapsan al
     * mismo valor `lapega`. Tiene que ser el espejo exacto de la expresion SQL
     * de `filtrarPorDistrito()`: si del lado PHP queda un separador que del lado
     * SQL se borra (o viceversa), el `LIKE` no encuentra nada.
     */
    private function normalizarDistrito(string $valor): string
    {
        return preg_replace('/[\s\-]+/u', '', mb_strtolower(trim($valor)));
    }

    /**
     * Aplica el filtro de distrito a una query sobre `propiedades`.
     *
     * El `whereHas` que elige los productores y el eager load restringido que
     * elige que propiedades se escriben en el archivo deben usar la misma
     * prediccion, asi que ambos delegan aca.
     */
    private function filtrarPorDistrito($query, string $distrito): void
    {
        $query->whereRaw(
            "LOWER(REPLACE(REPLACE(distrito, '-', ''), ' ', '')) LIKE ?",
            ['%'.$this->normalizarDistrito($distrito).'%']
        );
    }

    /**
     * Espejo en PHP de `aplicarFiltroCultivo()`, para no emitir una fila de
     * cultivo que la consulta no Habria traido. `LIKE` no distingue mayusculas
     * en SQLite ni en MySQL con la collation por defecto, asi que se compara
     * en minúsculas.
     */
    private function cultivoCoincide($cultivo, string $variedad, string $tipo): bool
    {
        if ($variedad !== '' && ! str_contains(mb_strtolower((string) $cultivo->variedad), mb_strtolower($variedad))) {
            return false;
        }

        if ($tipo !== '' && ! str_contains(mb_strtolower((string) $cultivo->tipo), mb_strtolower($tipo))) {
            return false;
        }

        return true;
    }

    private function writeExcelRow($sheet, int $row, array $data): void
    {
        foreach ($data as $i => $value) {
            $colLetter = $this->colLetter($i + 1);
            $cell = $sheet->getCell("{$colLetter}{$row}");

            if (is_float($value) || is_int($value)) {
                $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
            } elseif (is_null($value)) {
                $cell->setValueExplicit('', DataType::TYPE_STRING);
            } else {
                $cell->setValueExplicit((string) $value, DataType::TYPE_STRING);
            }
        }
    }

    private function colLetter(int $index): string
    {
        $letter = '';
        while ($index > 0) {
            $index--;
            $letter = chr(65 + ($index % 26)).$letter;
            $index = intdiv($index, 26);
        }

        return $letter;
    }
}
