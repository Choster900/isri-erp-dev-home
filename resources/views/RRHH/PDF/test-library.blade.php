<!DOCTYPE html>
<html>

<head>
    <title>Documento PDF</title>
    <style>
        .header {
            width: 100%;
            padding-bottom: 10px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table,
        .header-table th {
            border: 1px solid #000;
            border-bottom: none;
        }

        .header-table td {
            text-align: center;
            vertical-align: middle;
            padding: 3px;
            border: 1px solid #000;
        }

        .header img {
            max-height: 75px;
        }

        .header p {
            font-size: 12px;
            font-weight: bold;
            margin: 0;
        }

        .jobs {
            max-width: 100%;
            padding: 3px;
        }
    </style>
</head>

<body>
    <div class="header">
        <table class="header-table">
            <tr style="width: 100%;">
                <th style="width: 22%; max-width: 23%;">
                    <img src="{{ public_path('img/isri-gob.png') }}" alt="Logo">
                </th>
                <th style="width: 78%; max-width: 77%;">
                    <p>DEPARTAMENTO DE RECURSOS HUMANOS</p>
                    <p> {{ $title }} </p>
                    <p> {{ $depInfo }} </p>
                    <p> {{ $date }} </p>
                </th>
            </tr>
        </table>
        <table class="header-table">
            <tr style="width: 100%; background: #D1D5DB;">
                <th style="width: 8%; max-width: 8%; border: 1px solid #000; padding: 5px 0;">
                    <p style="font-size: 11px;">CODIGO</p>
                </th>
                <th style="width: 32%; max-width: 32%; border: 1px solid #000; padding: 5px 0;">
                    <p style="font-size: 11px;">NOMBRE</p>
                </th>
                <th style="width: 15%; max-width: 15%; border: 1px solid #000; padding: 5px 0;">
                    <p style="font-size: 11px;">DUI</p>
                </th>
                <th style="width: 10%; max-width: 10%; border: 1px solid #000; padding: 5px 0;">
                    <p style="font-size: 11px;">PENSIONADO</p>
                </th>
                <th style="width: 35%; max-width: 35%; border: 1px solid #000; padding: 5px 0;">
                    <p style="font-size: 11px;">PUESTO</p>
                </th>
            </tr>
            @foreach ($queryResult as $employee)
                <tr style="width: 100%;">
                    <td style="width: 8%; max-width: 8%; border: 1px solid #000;">
                        <p style="font-size: 11px; font-weight: normal;">{{ $employee['codigo_empleado'] }}</p>
                    </td>
                    <td style="width: 32%; max-width: 32%; border: 1px solid #000;">
                        <p style="font-size: 11px; font-weight: normal;">{{ $employee['persona']['nombre_apellido'] }}</p>
                    </td>
                    <td style="width: 15%; max-width: 15%; border: 1px solid #000;">
                        <p style="font-size: 11px; font-weight: normal;">{{ $employee['persona']['dui_persona'] }}</p>
                    </td>
                    <td style="width: 10%; max-width: 10%; border: 1px solid #000;">
                        <p style="font-size: 11px; font-weight: normal;">{{ $employee['pensionado_empleado'] == 1 ? 'SI' : 'NO' }}</p>
                    </td>
                    <td style="width: 35%; max-width: 35%; border: 1px solid #000;">
                        @foreach ($employee['plazas_asignadas'] as $index => $plaza)
                            <div class="jobs" style="{{ $index > 0 ? 'border-top: 1px solid #9CA3AF;' : '' }}">
                                <p style="font-size: 11px; font-weight: normal;">
                                    {{ $plaza['detalle_plaza']['plaza']['nombre_plaza'] }}
                                </p>
                                <p style="font-size: 11px; font-weight: normal;">
                                    {{ $plaza['dependencia']['centro_atencion']['codigo_centro_atencion'] .
                                        ' - (' .
                                        \Carbon\Carbon::parse($plaza['fecha_plaza_asignada'])->format('d/m/Y') .
                                        ($plaza['fecha_renuncia_plaza_asignada']
                                            ? ' - ' . \Carbon\Carbon::parse($plaza['fecha_renuncia_plaza_asignada'])->format('d/m/Y') . ')'
                                            : ($employee['id_estado_empleado'] === 1
                                                ? ')'
                                                : ' - sin registro)')) }}
                                </p>
                            </div>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</body>

</html>
