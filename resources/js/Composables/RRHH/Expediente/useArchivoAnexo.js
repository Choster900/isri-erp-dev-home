import { ref } from "vue";

export const useArchivoAnexo = () => {
    const idPersona = ref(null);
    const idTipoMine = ref(null);
    const idArchivoAnexo = ref(null);
    const idTipoArchivoAnexo = ref(null);
    const fileArchivoAnexo = ref(null);
    const nombreArchivoAnexo = ref(null);
    const descripcionArchivoAnexo = ref(null);

    /**
     * 
     * @return Response|null
     */
    const createArchivoAnexoRequest = () => {
        return new Promise(async (resolve, reject) => {
            try {
                const formData = new FormData();
                formData.append("idPersona", idPersona.value);
                formData.append("idTipoArchivoAnexo", idTipoArchivoAnexo.value);
                formData.append("descripcionArchivoAnexo",descripcionArchivoAnexo.value);
                formData.append("fileArchivoAnexo",fileArchivoAnexo.value);

                const resp = await axios.post("/createArchivoAnexo", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });

                console.log(resp);
                resolve(resp);

            } catch (error) {
                console.error(error);
                reject(error);
            }
        });
    };

    return {
        idPersona,
        idTipoMine,
        idArchivoAnexo,
        idTipoArchivoAnexo,
        fileArchivoAnexo,
        nombreArchivoAnexo,
        descripcionArchivoAnexo,
        createArchivoAnexoRequest,
    };
};
