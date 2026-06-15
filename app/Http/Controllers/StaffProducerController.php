<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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

        $query = User::query();

        if ($dni) {
            $query->where('dni', 'like', "%{$dni}%");
        }
        if ($name) {
            $query->where('name', 'like', "%{$name}%");
        }
        if ($distrito) {
            $query->whereHas('propiedades', function ($q) use ($distrito) {
                $normalizedDistrito = strtolower(str_replace(' ', '-', $distrito));
                $q->whereRaw("LOWER(REPLACE(distrito, ' ', '-')) = ?", [$normalizedDistrito]);
            });
        }
        if ($rut) {
            $query->whereHas('propiedades', function ($q) use ($rut) {
                $q->where('rut', true)
                    ->where('rut_valor', 'like', "%{$rut}%");
            });
        }

        if ($variedad || $tipo) {
            $query->whereHas('propiedades.cultivos', function ($q) use ($variedad, $tipo) {
                if ($variedad) {
                    $q->where('variedad', 'like', "%{$variedad}%");
                }
                if ($tipo) {
                    $q->where('tipo', 'like', "%{$tipo}%");
                }
            });
        }

        $producers = $query->with(['propiedades.cultivos'])->get();

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

        // Generar Excel (HTML table que Excel puede abrir)
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

        // Fecha y hora de exportación
        $fechaExport = date('d/m/Y H:i').' hs';

        // Nombre de archivo dinámico
        $dateStr = date('Y-m-d');
        if ($searchType && $searchValue) {
            $filename = 'productores_'.strtolower(str_replace(' ', '_', $searchValue)).'_'.$dateStr.'.xlsx';
        } else {
            $filename = 'productores_todos_'.$dateStr.'.xlsx';
        }

        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
        <head>
            <meta charset="UTF-8">
            <style>
                table { border-collapse: collapse; width: 100%; }
                th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
                th { background-color: #1e40af; color: white; }
                tr:nth-child(even) { background-color: #f8fafc; }
                .title { font-size: 18px; font-weight: bold; color: #1e40af; margin-bottom: 10px; }
                .date { font-size: 12px; color: #64748b; margin-bottom: 20px; }
            </style>
        </head>
        <body>
        <div class="title">'.htmlspecialchars($titulo).'</div>
        <div class="date">Fecha de exportación: '.$fechaExport.'</div>
        <table>
        <thead>
        <tr>';

        foreach ($headers as $header) {
            $html .= '<th>'.htmlspecialchars($header).'</th>';
        }
        $html .= '</tr></thead><tbody>';

        foreach ($producers as $producer) {
            $row = [
                htmlspecialchars($producer->name),
                htmlspecialchars($producer->dni ?? ''),
                htmlspecialchars($producer->propiedades->where('rut', true)->first()?->rut_valor ?? ''),
                htmlspecialchars($producer->telefono ?? ''),
                htmlspecialchars($producer->email),
            ];

            if ($searchType === 'variedad') {
                $variedadData = $this->getVariedadData($producer, $variedad);
                $row[] = htmlspecialchars($variedadData['variedad']);
                $row[] = $variedadData['hectareas'];
            } elseif ($searchType === 'tipo') {
                $tipoData = $this->getTipoData($producer, $tipo);
                $row[] = htmlspecialchars($tipoData['tipo']);
                $row[] = $tipoData['hectareas'];
            } elseif ($searchType === 'distrito') {
                $distritos = $producer->propiedades->pluck('distrito')->filter()->unique()->values()->toArray();
                $row[] = htmlspecialchars(implode(', ', $distritos));
            }

            $html .= '<tr><td>'.implode('</td><td>', $row).'</td></tr>';
        }

        $html .= '</tbody></table></body></html>';

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
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
