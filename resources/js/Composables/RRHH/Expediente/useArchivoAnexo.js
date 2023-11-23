import { ref } from "vue";
import Swal from "sweetalert2";
import { executeRequest } from "@/plugins/requestHelpers.js";

export const useArchivoAnexo = (context) => {
    const errorsData = ref([]);
    const idPersona = ref("");
    const idTipoMine = ref(null);
    const sizeArchivoAnexo = ref(null);
    const idArchivoAnexo = ref(null);
    const fileArchivoAnexo = ref("");
    const idTipoArchivoAnexo = ref('');
    const nombreArchivoAnexo = ref(null);
    const descripcionArchivoAnexo = ref(null);
    const dataArrayPersona = ref(null);
    const isLoadingRequestPersona = ref(false);

    const getPersonasById = async (idPer) => {
        isLoadingRequestPersona.value = true;
        try {
            const resp = await axios.post("/getArchivosAnexosByUser", {
                id_persona: idPer,
            });
            // console.log(resp);
            dataArrayPersona.value = resp.data;
            isLoadingRequestPersona.value = false;
        } catch (error) {
            console.error(error);
        }
    };
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
                if (error.response.status === 422) {
                    let data = error.response.data.errors;
                    var myData = new Object();
                    for (const errorBack in data) {
                        myData[errorBack] = data[errorBack][0];
                    }
                    errorsData.value = myData;
                    console.table(errorsData.value);
                    setTimeout(() => {
                        errorsData.value = [];
                    }, 5000);
                }
                reject(error);
            }
        });
    };

    const createArchivoAnexo = async (thereIsIdPersona) => {
        const confirmed = await Swal.fire({
            title: '<p class="text-[18pt] text-center">¿Esta seguro de agregar un nuevo Anexo?</p>',
            icon: "question",
            iconHtml: `<lord-icon src="https://cdn.lordicon.com/enzmygww.json" trigger="loop" delay="500" colors="primary:#121331" style="width:100px;height:100px"></lord-icon>`,
            confirmButtonText: "Si, Agregar",
            confirmButtonColor: "#001b47",
            cancelButtonText: "Cancelar",
            showCancelButton: true,
            showCloseButton: true,
        });
        if (confirmed.isConfirmed) {
            executeRequest(
                createArchivoAnexoRequest(thereIsIdPersona),
                "¡El anexo se ha agregado correctamente! Espere mientras se redirecciona al inicio"
            );
        }
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
                if (error.response.status === 422) {
                    let data = error.response.data.errors;
                    var myData = new Object();
                    for (const errorBack in data) {
                        myData[errorBack] = data[errorBack][0];
                    }
                    errorsData.value = myData;
                    console.table(errorsData.value);
                    setTimeout(() => {
                        errorsData.value = [];
                    }, 5000);
                }
                reject(error);
            }
        });
    };

    const updateArchivoAnexo = async () => {
        const confirmed = await Swal.fire({
            title: '<p class="text-[18pt] text-center">¿Esta seguro de editar el anexo?</p>',
            icon: "question",
            iconHtml: `<lord-icon src="https://cdn.lordicon.com/enzmygww.json" trigger="loop" delay="500" colors="primary:#121331" style="width:100px;height:100px"></lord-icon>`,
            confirmButtonText: "Si, Editar",
            confirmButtonColor: "#001b47",
            cancelButtonText: "Cancelar",
            showCancelButton: true,
            showCloseButton: true,
        });
        if (confirmed.isConfirmed) {
            executeRequest(
                updateArchivoAnexoRequest(),
                "¡El anexo se ha acutalizado correctamente! Espere mientras se redirecciona al inicio"
            );
        }
    };

    const delteArchivoAnexo = async () => {
        const confirmed = await Swal.fire({
            title: '<p class="text-[17pt] text-center">¿Esta seguro de eliminar el anexo?. los cambios no podran revertirse y perdera el archivo</p>',
            icon: "question",
            iconHtml: `<lord-icon src="https://cdn.lordicon.com/enzmygww.json" trigger="loop" delay="500" colors="primary:#121331" style="width:100px;height:100px"></lord-icon>`,
            confirmButtonText: "Si, Editar",
            confirmButtonColor: "#F34541",
            cancelButtonText: "Cancelar",
            showCancelButton: true,
            showCloseButton: true,
        });
        if (confirmed.isConfirmed) {
            executeRequest(
                delteArchivoAnexoRequest(),
                "¡El anexo se ha eliminado correctamente! Espere mientras se redirecciona al inicio"
            );
        }
    };

    const delteArchivoAnexoRequest = (idArchivoAnexoForDelete) => {
        return new Promise(async (resolve, reject) => {
            try {
                const resp = await axios.post("/deleteArchivoAnexoById", {
                    idArchivoAnexo: idArchivoAnexoForDelete,
                });
                context.emit("refresh-information");
                resolve(resp);
            } catch (error) {
                console.log(error.message);
                reject(error);
            }
        });
    };

    return {
        isLoadingRequestPersona,
        idPersona,
        idTipoMine,
        errorsData,
        idArchivoAnexo,
        fileArchivoAnexo,
        sizeArchivoAnexo,
        delteArchivoAnexo,
        idTipoArchivoAnexo,
        nombreArchivoAnexo,
        getPersonasById,
        createArchivoAnexo,
        updateArchivoAnexo,
        descripcionArchivoAnexo,
        delteArchivoAnexoRequest,
        createArchivoAnexoRequest,
        dataArrayPersona,
        updateArchivoAnexoRequest,
    };
};
