<!DOCTYPE html>
<html>

<head>
    <title>Documento PDF</title>
    
    @vite('resources/css/app.css')

    <style>
        
    </style>
</head>

<body>
    <div id="principal" class="py-2 mb-2">
        <div class="flex w-full min-h-[80px]">
            <!-- Columna 1 -->
            <div class="w-[23%] flex justify-center items-center border border-black p-1">
                <img src="{{ public_path('img/isri-gob.png')  }}" alt="Logo del instituto" class="w-full my-auto">
            </div>
            <!-- Columna 2 -->
            <div
                class="w-[77%] justify-center items-center border-y border-r border-black pb-[10px] flex flex-col min-h-full">
                <div class="flex flex-col justify-center items-center h-full">
                    <p class="text-center text-[12px] font-bold text-red-300">DEPARTAMENTO DE RECURSOS HUMANOS</p>
                    <p class="text-center text-[12px] font-bold">DEPARTAMENTO DE RECURSOS HUMANOS</p>
                    <p class="text-center text-[12px] font-bold">DEPARTAMENTO DE RECURSOS HUMANOS</p>
                    <p class="text-center text-[12px] font-bold">DEPARTAMENTO DE RECURSOS HUMANOS</p>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
