import { ref } from "vue";
import Swal from "sweetalert2";
import { executeRequest } from "@/plugins/requestHelpers.js";
import { useFileHandling } from "@/Composables/RRHH/Expediente/useFileHandling";

export const useArchivoAnexo = () => {
    const errorsData = ref([]);
    const idPersona = ref("");
    const idTipoMine = ref(null);
    const sizeArchivoAnexo = ref(null);
    const idArchivoAnexo = ref(null);
    const fileArchivoAnexo = ref("");
    const idTipoArchivoAnexo = ref("");
    const nombreArchivoAnexo = ref(null);
    const descripcionArchivoAnexo = ref(null);
    const dataArrayPersona = ref(null);
    const isLoadingRequestPersona = ref(false);
    const fileHandling = useFileHandling();
    const objectPersona = ref([])
    const stateView = ref(0) // Valida que este agregando o viendo los archivos del usuario
    const personaWhoWasSelected = ref([])// Contiene el array de la persona a quien se esta editando

    const actionOption = ref(1); // Optio 1 es igual a agregar 2 es igual a editar
    const fileInput = ref('');
    const urlArchivoAnexo = ref(null);
    const nameArchivoAnexo = ref(null);
    const file = ref(null);
    const tipoMine = ref(null);
    const sizeArchivo = ref(null);
    const openFileInput = () => {
        fileInput.value.click();
    };

    const handleFileChange = () => {
        const selectedFile = fileInput.value.files[0];
        tipoMine.value = selectedFile.type
        sizeArchivo.value = selectedFile.size
        nameArchivoAnexo.value = selectedFile.name;
        if (selectedFile) {
            const fileType = selectedFile.type;
            if (fileType.includes("image")) {
                setImageData(selectedFile);
            } else if (fileType === "application/pdf") {
                setPdfData(selectedFile);
            } else {
                console.error("Tipo de archivo no compatible.");
            }
        }
    };

    const handleDragOver = (event) => {
        event.preventDefault();
    };

    const handleDrop = (event) => {
        event.preventDefault();
        const selectedFile = event.dataTransfer.files[0];
        nameArchivoAnexo.value = selectedFile.name;
        if (selectedFile) {
            const fileType = selectedFile.type;
            if (fileType.includes("image")) {
                setImageData(selectedFile);
            } else if (fileType === "application/pdf") {
                setPdfData(selectedFile);
            } else {
                console.error("Tipo de archivo no compatible.");
            }
        }
        fileInput.value = null;
    };

    const setImageData = (selectedFile) => {
        urlArchivoAnexo.value = URL.createObjectURL(selectedFile);
        file.value = selectedFile;
        console.log("Imagen seleccionada:", selectedFile.name);
    };

    const setPdfData = (selectedFile) => {
        urlArchivoAnexo.value = URL.createObjectURL(selectedFile);
        file.value = selectedFile;
        console.log("PDF seleccionado:", selectedFile.name);
    };

    const deleteFile = () => {
        console.log(file);
        file.value = '';
        fileInput.value = '';
        urlArchivoAnexo.value = '';
        nameArchivoAnexo.value = '';
        console.log("Archivo eliminado.");
    };

    function crealFormVar() {
        descripcionArchivoAnexo.value = "";
        idTipoMine.value = "";
        sizeArchivoAnexo.value = "";
        fileArchivoAnexo.value = "";
        idTipoArchivoAnexo.value = "";
        stateView.value = 0
    }

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
                const resp = await axios.post(
                    "/createArchivoAnexo",
                    {
                        idPersona: idPersona.value,
                        idTipoMine: idTipoMine.value,
                        fileArchivoAnexo: fileArchivoAnexo.value,
                        sizeArchivoAnexo: sizeArchivoAnexo.value,
                        idTipoArchivoAnexo: idTipoArchivoAnexo.value,
                        descripcionArchivoAnexo: descripcionArchivoAnexo.value,
                    },
                    {
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    }
                );

                if (thereIsIdPersona) {
                    //   context.emit("refresh-information");
                } else {
                    // context.emit("cerrar-modal");
                }
                console.log(resp.data.persona);
                const dataFromBack = resp.data;


                personaWhoWasSelected.value.push({ value: resp.data.persona.id_persona, label: resp.data.persona.nombre_apellido });
                idPersona.value = resp.data.persona.id_persona

                // Verifica si `objectPersona.value` es un array, si no, conviértelo en uno
                if (!Array.isArray(objectPersona.value)) {
                    objectPersona.value = [];
                }

                // Comprueba si `dataFromBack` es un array
                if (Array.isArray(dataFromBack)) {
                    // Si es un array, usa spread operator para agregar los elementos individualmente
                    objectPersona.value.push(...dataFromBack);
                } else {
                    // Si no es un array, simplemente agrega `dataFromBack` a `objectPersona.value`
                    objectPersona.value.push(dataFromBack);
                }
                
                crealFormVar();
                fileHandling.deleteFile();
                resolve(resp);
            } catch (error) {
                console.log(error);
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



    /**
     *
     * @returns
     */
    const updateArchivoAnexoRequest = () => {
        return new Promise(async (resolve, reject) => {
            try {
                const resp = await axios.post(
                    "/modifiedArchivoAnexo",
                    {
                        idPersona: idPersona.value,
                        idTipoMine: idTipoMine.value,
                        idArchivoAnexo: idArchivoAnexo.value,
                        sizeArchivoAnexo: sizeArchivoAnexo.value,
                        idTipoArchivoAnexo: idTipoArchivoAnexo.value,
                        descripcionArchivoAnexo: descripcionArchivoAnexo.value,
                        fileArchivoAnexo: fileArchivoAnexo.value,
                    },
                    {
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    }
                );
                //    context.emit("refresh-information");
                console.log(resp.data);
                console.log(objectPersona.value.find(index => index.id_archivo_anexo == resp.data.id_archivo_anexo));
                objectPersona.value.find(index => index.id_archivo_anexo == resp.data.id_archivo_anexo).url_archivo_anexo = resp.data.url_archivo_anexo
                objectPersona.value.find(index => index.id_archivo_anexo == resp.data.id_archivo_anexo).nombre_archivo_anexo = resp.data.nombre_archivo_anexo
                objectPersona.value.find(index => index.id_archivo_anexo == resp.data.id_archivo_anexo).descripcion_archivo_anexo = resp.data.descripcion_archivo_anexo
                objectPersona.value.find(index => index.id_archivo_anexo == resp.data.id_archivo_anexo).id_tipo_archivo_anexo = resp.data.id_tipo_archivo_anexo
                objectPersona.value.find(index => index.id_archivo_anexo == resp.data.id_archivo_anexo).id_tipo_mime = resp.data.id_tipo_mime


                /*
                urlArchivoAnexo.value = idFile.url_archivo_anexo
                 idArchivoAnexo.value = idFile.id_archivo_anexo
            idTipoArchivoAnexo.value = idFile.id_tipo_archivo_anexo
            nombreArchivoAnexo.value = idFile.nombre_archivo_anexo
            nameArchivoAnexo.value = idFile.nombre_archivo_anexo
            descripcionArchivoAnexo.value = idFile.descripcion_archivo_anexo
             */
                crealFormVar();
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



    /* const delteArchivoAnexo = async () => {
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
    }; */

    const delteArchivoAnexoRequest = () => {
        return new Promise(async (resolve, reject) => {
            try {
                const resp = await axios.post("/deleteArchivoAnexoById", {
                    idArchivoAnexo: idArchivoAnexo.value,
                });
                //  context.emit("refresh-information");


                const index = objectPersona.value.findIndex(item => item.id_archivo_anexo === idArchivoAnexo.value);
                if (index !== -1) {
                    objectPersona.value.splice(index, 1);
                }


                resolve(resp);
            } catch (error) {
                console.log(error.message);
                reject(error);
            }
        });
    };

    const downloadFile = (fileUrl) => {
        const link = document.createElement('a');
        link.href = fileUrl;
        link.download = fileUrl.split('/').pop(); // Extrae el nombre del archivo de la URL
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    const openInNewTab = (fileUrl) => {
        window.open(fileUrl, '_blank');
    }

    return {
        downloadFile,
        openInNewTab,
        isLoadingRequestPersona,
        objectPersona,
        idPersona,
        idTipoMine,
        errorsData,
        idArchivoAnexo,
        fileArchivoAnexo,
        sizeArchivoAnexo,

        idTipoArchivoAnexo,
        nombreArchivoAnexo,
        stateView,
        getPersonasById,
        descripcionArchivoAnexo,
        delteArchivoAnexoRequest,
        createArchivoAnexoRequest,
        dataArrayPersona,
        updateArchivoAnexoRequest,
        file,
        fileInput,
        handleDrop,
        deleteFile,
        openFileInput,
        handleDragOver,
        urlArchivoAnexo,
        personaWhoWasSelected,
        tipoMine,
        sizeArchivo,
        handleFileChange,
        actionOption,
        nameArchivoAnexo,
    };
};
