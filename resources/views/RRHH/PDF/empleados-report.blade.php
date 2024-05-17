<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- @vite('resources/css/app.css') --}}
</head>

<body>
    <div id="principal" class="py-2 mb-2">

        <div class="flex w-full min-h-[80px]">
            <!-- Columna 1 -->
            <div class="w-[23%] flex justify-center items-center border border-black p-1">
                <img src="{{ public_path('img/isri-gob.png') }}" alt="Logo del instituto" class="w-full my-auto">
            </div>
            <!-- Columna 2 -->
            <div
                class="w-[77%] justify-center items-center border-y border-r border-black pb-[10px] flex flex-col min-h-full">
                <div class="flex flex-col justify-center items-center h-full">
                    <p class="font-[Bembo] text-center text-[12px] font-bold">DEPARTAMENTO DE RECURSOS HUMANOS</p>
                    <p class="font-[Bembo] text-center text-[12px] font-bold">{{ $title }}</p>
                    <p class="font-[Bembo] text-center text-[12px] font-bold">{{ $depInfo }}</p>
                    <p class="font-[Bembo] text-center text-[12px] font-bold">{{ $date }}</p>
                </div>
            </div>
        </div>

        <!-- Encabezado de la tabla -->
        <table class="w-full border-collapse border border-black bg-gray-200" style="page-break-inside: avoid;">
            <thead>
                <tr>
                    <th class="w-[8%] border-r border-black py-1 text-center">
                        <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px] font-bold">CODIGO</p>
                    </th>
                    <th class="w-[32%] border-r border-black py-1 text-center">
                        <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px] font-bold">NOMBRE</p>
                    </th>
                    <th class="w-[10%] border-r border-black py-1 text-center">
                        <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px] font-bold">DUI</p>
                    </th>
                    <th class="w-[15%] border-r border-black py-1 text-center">
                        <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px] font-bold">PENSIONADO</p>
                    </th>
                    <th class="w-[35%] py-1 text-center">
                        <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px] font-bold">PUESTO</p>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($queryResult as $index => $employee)
                    <tr class="border-x border-y border-black" style="page-break-inside: avoid;">
                        <!-- Code -->
                        <td class="w-[8%] border-r border-black text-center">
                            <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px] px-0.5">
                                {{ $employee['codigo_empleado'] }}
                            </p>
                        </td>
                        <!-- Name -->
                        <td class="w-[32%] border-r border-black text-center">
                            <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px]">
                                {{ $employee['persona']['papellido_persona'] }}
                                {{ $employee['persona']['sapellido_persona'] }}
                                {{ $employee['persona']['tapellido_persona'] }}
                                ,
                                {{ $employee['persona']['pnombre_persona'] }}
                                {{ $employee['persona']['snombre_persona'] }}
                                {{ $employee['persona']['tnombre_persona'] }}
                            </p>
                        </td>
                        <!-- ID -->
                        <td class="w-[10%] border-r border-black text-center">
                            <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px]">
                                {{ $employee['persona']['dui_persona'] }}
                            </p>
                        </td>
                        <!-- Retirement -->
                        <td class="w-[15%] border-r border-black text-center">
                            <p class="mb-[10px] mt-[-5px] font-[MuseoSans] text-[10px]">
                                {{ $employee['pensionado_empleado'] === 1 ? 'SI' : 'NO' }}
                            </p>
                        </td>
                        <!-- Job positions -->
                        <td class="w-[35%] min-h-[45px]">
                            <div class="w-full h-full flex flex-col justify-center items-center pb-1">
                                @foreach ($employee['plazas_asignadas'] as $index => $plaza)
                                    <p
                                        class="font-[MuseoSans] text-[10px]{{ $index > 0 ? ' border-t border-slate-400 w-full text-center' : '' }}">
                                        {{ $plaza['detalle_plaza']['plaza']['nombre_plaza'] }}
                                    </p>
                                    <p class="font-[MuseoSans] text-[10px] pb-2">
                                        {{ $plaza['dependencia']['centro_atencion']['codigo_centro_atencion'] }} -
                                        {{ '(' }}
                                        {{ $plaza['fecha_plaza_asignada'] }}
                                        {{ $plaza['fecha_renuncia_plaza_asignada'] ? ' - ' . $plaza['fecha_renuncia_plaza_asignada'] . ')' : ($employee['id_estado_empleado'] === 1 ? ')' : ' - sin registro)') }}
                                    </p>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>

</html>
