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
                $normalized = strtolower(str_replace(' ', '-', trim($distrito)));
                $search = str_replace('-', '', $normalized);

                $q->whereHas('propiedades', function ($sub) use ($search) {
                    $sub->whereRaw(
                        "LOWER(REPLACE(REPLACE(distrito, '-', ''), ' ', '')) LIKE ?",
                        ["%{$search}%"]
                    );
                });
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
                        ->whereRaw(
                            'CAST(rut_valor AS CHAR) LIKE ?',
                            ["%{$search}%"]
                        );
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
                'rut_archivo_url' => $prop->rut_archivo ? route('propiedades.rut', $prop) : null,
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
        $dni = trim((string) $request->get('dni', ''));
        $name = trim((string) $request->get('name', ''));
        $distrito = trim((string) $request->get('distrito', ''));
        $variedad = trim((string) $request->get('variedad', ''));
        $tipo = trim((string) $request->get('tipo', ''));
        $rut = trim((string) $request->get('rut', ''));

        $producers = User::query()
            ->distinct()

            ->when($dni !== '', fn ($q) => $q->where('users.dni', 'like', "%{$dni}%"))

            ->when($name !== '', fn ($q) => $q->where('users.name', 'like', "%{$name}%"))

            ->when($distrito !== '', function ($q) use ($distrito) {
                $normalized = strtolower(str_replace(' ', '-', trim($distrito)));
                $search = str_replace('-', '', $normalized);

                $q->whereHas('propiedades', function ($sub) use ($search) {
                    $sub->whereRaw(
                        "LOWER(REPLACE(REPLACE(distrito, '-', ''), ' ', '')) LIKE ?",
                        ["%{$search}%"]
                    );
                });
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
                        ->whereRaw(
                            'CAST(rut_valor AS CHAR) LIKE ?',
                            ["%{$search}%"]
                        );
                });
            })

            ->with(['propiedades.cultivos'])
            ->get();

        // Determinar tipo de búsqueda para headers adicionales
        $searchType = null;
        $searchValue = null;
        if ($variedad) {
            $searchType = 'variedad';
            $searchValue = $variedad;
        } elseif ($tipo) {
            $searchType = 'tipo';
            $searchValue = $tipo;
        } elseif ($distrito) {
            $searchType = 'distrito';
            $searchValue = $distrito;
        }

        $headers = ['Nombre', 'DNI', 'RUT', 'Teléfono', 'Email'];

        if ($searchType === 'variedad') {
            $headers = array_merge($headers, ['Variedad', 'Hectáreas']);
        } elseif ($searchType === 'tipo') {
            $headers = array_merge($headers, ['Tipo', 'Hectáreas']);
        } elseif ($searchType === 'distrito') {
            $headers = array_merge($headers, ['Distrito']);
        }

        // Título dinámico según tipo de búsqueda
        $titulo = 'Listado de Productores';
        if ($searchType === 'distrito' && $searchValue) {
            $titulo = 'Productores del Distrito '.$searchValue;
        } elseif ($searchType === 'variedad' && $searchValue) {
            $titulo = 'Productores que cultivan '.$searchValue;
        } elseif ($searchType === 'tipo' && $searchValue) {
            $titulo = 'Productores de tipo '.$searchValue;
        }

        $fechaExport = date('d/m/Y H:i').' hs';

        $dateStr = date('Y-m-d');
        if ($searchType && $searchValue) {
            $filename = 'productores_'.strtolower(str_replace(' ', '_', $searchValue)).'_'.$dateStr.'.xlsx';
        } else {
            $filename = 'productores_todos_'.$dateStr.'.xlsx';
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Productores');

        $lastCol = $this->colLetter(count($headers));

        // Título
        $sheet->setCellValue('A1', $titulo);
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        // Fecha
        $sheet->setCellValue('A2', "Fecha de exportación: {$fechaExport}");
        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->getStyle('A2')->getFont()->setSize(10)->getColor()->setARGB('FF64748B');

        // Headers fila 4 con estilo
        $headerRow = 4;
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue("{$col}{$headerRow}", $header);
            $sheet->getStyle("{$col}{$headerRow}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E40AF']],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            ]);
            $col++;
        }

        // Datos desde fila 5
        $rowNum = 5;
        foreach ($producers as $producer) {
            $data = [
                $producer->name,
                $producer->dni ?? '',
                $producer->propiedades->where('rut', true)->first()?->rut_valor ?? '',
                $producer->telefono ?? '',
                $producer->email,
            ];

            if ($searchType === 'variedad') {
                $variedadData = $this->getVariedadData($producer, $variedad);
                $data[] = $variedadData['variedad'];
                $data[] = $variedadData['hectareas'];
            } elseif ($searchType === 'tipo') {
                $tipoData = $this->getTipoData($producer, $tipo);
                $data[] = $tipoData['tipo'];
                $data[] = $tipoData['hectareas'];
            } elseif ($searchType === 'distrito') {
                $distritos = $producer->propiedades->pluck('distrito')->filter()->unique()->values()->toArray();
                $data[] = implode(', ', $distritos);
            }

            $col = 'A';
            foreach ($data as $value) {
                $cell = $sheet->getCell("{$col}{$rowNum}");
                if (is_float($value) || is_int($value)) {
                    $cell->setValueExplicit($value, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                } else {
                    $cell->setValue($value);
                }
                $col++;
            }
            $rowNum++;
        }

        // Auto-size columns
        $col = 'A';
        for ($i = 0; $i < count($headers); $i++) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
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

    private function colLetter(int $index): string
    {
        return chr(64 + $index);
    }

    private function getVariedadData($producer, $variedad)
    {
        $totalHectareas = 0;
        $variedadesEncontradas = [];

        foreach ($producer->propiedades as $prop) {
            foreach ($prop->cultivos as $cult) {
                if (stripos($cult->variedad, $variedad) !== false) {
                    $variedadesEncontradas[] = $cult->variedad;
                    $totalHectareas += $cult->hectareas;
                }
            }
        }

        return [
            'variedad' => implode(', ', array_unique($variedadesEncontradas)) ?: $variedad,
            'hectareas' => $totalHectareas,
        ];
    }

    private function getTipoData($producer, $tipo)
    {
        $totalHectareas = 0;
        $tiposEncontrados = [];

        foreach ($producer->propiedades as $prop) {
            foreach ($prop->cultivos as $cult) {
                if (stripos($cult->tipo, $tipo) !== false) {
                    $tiposEncontrados[] = $cult->tipo;
                    $totalHectareas += $cult->hectareas;
                }
            }
        }

        return [
            'tipo' => implode(', ', array_unique($tiposEncontrados)) ?: $tipo,
            'hectareas' => $totalHectareas,
        ];
    }
}
