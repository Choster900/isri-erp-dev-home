import { ref } from "vue";

export const useArchivoAnexo = (context) => {
    const idPersona = ref(null);
    const idTipoMine = ref(null);
    const sizeArchivoAnexo = ref(null);
    const idArchivoAnexo = ref(null);
    const idTipoArchivoAnexo = ref(null);
    const fileArchivoAnexo = ref(null);
    const nombreArchivoAnexo = ref(null);
    const descripcionArchivoAnexo = ref(null);

    /**
     *
     * @returns
     */
    const createArchivoAnexoRequest = (thereIsIdPersona) => {
        return new Promise(async (resolve, reject) => {
            try {
                const formData = new FormData();
                formData.append("idPersona", idPersona.value);
                formData.append("idTipoMine", idTipoMine.value);
                formData.append("sizeArchivoAnexo", sizeArchivoAnexo.value);
                formData.append("idTipoArchivoAnexo", idTipoArchivoAnexo.value);
                formData.append(
                    "descripcionArchivoAnexo",
                    descripcionArchivoAnexo.value
                );
                formData.append("fileArchivoAnexo", fileArchivoAnexo.value);

                const resp = await axios.post("/createArchivoAnexo", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });

                if (thereIsIdPersona) {
                    context.emit("refresh-information");
                } else {
                    context.emit("cerrar-modal");
                }
                resolve(resp);
            } catch (error) {
                console.error(error);
                reject(error);
            }
        });
    };

    /**
     *
     * @returns
     */
    const updateArchivoAnexoRequest = () => {
        return new Promise(async (resolve, reject) => {
            try {
                const formData = new FormData();
                formData.append("idPersona", idPersona.value);
                formData.append("idTipoMine", idTipoMine.value);
                formData.append("idArchivoAnexo", idArchivoAnexo.value);
                formData.append("sizeArchivoAnexo", sizeArchivoAnexo.value);
                formData.append("idTipoArchivoAnexo", idTipoArchivoAnexo.value);
                formData.append(
                    "descripcionArchivoAnexo",
                    descripcionArchivoAnexo.value
                );
                formData.append("fileArchivoAnexo", fileArchivoAnexo.value);

                const resp = await axios.post(
                    "/modifiedArchivoAnexo",
                    formData,
                    {
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    }
                );
                context.emit("refresh-information");
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
        sizeArchivoAnexo,
        createArchivoAnexoRequest,
        updateArchivoAnexoRequest,
    };
};
