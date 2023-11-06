import axios from "axios";
import { onMounted, ref } from "vue";

export const useTipoArchivoAnexo = () => {
    const objTipoArchivoAnexo = ref([])
    /**
     * 
     * @returns Obtiene todos los tipos de archivos de anexos
     */
    const getAllTipoArchivoAnexos = async () => {
        return new Promise(async (resolve, reject) => {
            try {
                const resp = await axios.get("/getAllTipoArchivoAnexos");
                objTipoArchivoAnexo.value = resp.data // Asignamos data a objTipoArchivoAnexo
                resolve(resp); // Resolvemos la promesa con la respuesta exitosa
            } catch (error) {
               console.log(error);
                reject(error); // Rechazamos la promesa en caso de excepción
            }
        });
    };
    onMounted(async() => {
        await getAllTipoArchivoAnexos();
    })
    
    return{
        objTipoArchivoAnexo,
    }
};
