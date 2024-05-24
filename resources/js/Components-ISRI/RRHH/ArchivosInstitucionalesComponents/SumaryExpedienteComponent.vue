<template>
    <div class="container mx-auto ">
       <div class="border-l-4 border-gray-200">
            <!-- Card 1 -->
            <div v-for="(item, i) in userData" :key="i"
                class="transform transition duration-300 hover:-translate-y-1 hover:border ml-8 relative flex items-center px-6 py-4 bg-white shadow-md rounded-lg mb-6">
                <!-- Dot Following the Left Vertical Line -->

                <!-- Line that connects the box with the vertical line -->
                <div class="w-1 h-full bg-[#001c48]/80 absolute -left-8"></div>

                <!-- Content shown in the box -->
                <div class="flex-1 flex gap-4 items-center">

                    <div class="w-1/3">
                        <img v-if="item.id_tipo_mime == 2" class="w-full rounded-lg cursor-pointer"
                            :src="item.url_archivo_anexo" alt="" />
                        <iframe v-else-if="item.id_tipo_mime == 1" class="w-full rounded-lg"
                            :src="item.url_archivo_anexo" alt=""></iframe>
                        <!--  <button @click="openInNewTab(item.url_archivo_anexo)"
                            class="block w-full mt-2 bg-blue-500 hover:bg-blue-700 text-white text-xs font-bold py-2 px-4 rounded">Abrir
                            archivo en nueva pestaña</button> -->
                    </div>


                    <div>
                        <h2 class="text-sm font-semibold text-gray-900">
                            {{ item . tipo_archivo_anexo . nombre_tipo_archivo_anexo }}</h2>
                        <p class="text-xs text-gray-600">{{ item . nombre_archivo_anexo }}</p>
                        <p class="text-xs text-gray-500">Peso: {{ fetchFileSize(item.url_archivo_anexo) }} KB</p>
                        <p class="text-xs text-gray-500">Fecha de Subida: 23/05/2024</p>
                    </div>
                </div>

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6 mx-2 hover:text-indigo-700 cursor-pointer"
                    @click="openInNewTab(item.url_archivo_anexo)">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                </svg>


                <svg @click.prevent="downloadFile(item.url_archivo_anexo)" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                    class="size-6 hover:text-indigo-700 cursor-pointer">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>

            </div>
        </div>
    </div>
</template>

<script>
    import ModalToShowFiles from '@/Components-ISRI/AllModal/ModalToShowFiles.vue'
    export default {
        props: ["userData"],
        components: {
            ModalToShowFiles
        },
        setup() {

            const downloadFile = (fileUrl) => {
                const link = document.createElement('a');
                link.href = fileUrl;
                link.download = fileUrl.split('/').pop(); // Extrae el nombre del archivo de la URL
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            };

            const openInNewTab = (fileUrl) => {
                window.open(fileUrl, '_blank');
            };

            const fetchFileSize = async (fileUrl) => {
      try {
        const response = await axios.head(fileUrl);
        const contentLength = response.headers['content-length'];
        if (contentLength) {
          return (parseInt(contentLength) / 1024).toFixed(2); // Convertir bytes a kilobytes y redondear a 2 decimales
        }
      } catch (error) {
        console.error('Error al obtener el tamaño del archivo:', error);
        return 'Desconocido';
      }
    };

            return {
                fetchFileSize,
                downloadFile,
                openInNewTab
            };
        }
    }
</script>

<style lang="scss" scoped></style>
