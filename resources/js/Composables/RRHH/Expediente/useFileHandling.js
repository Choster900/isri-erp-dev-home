import { ref } from "vue";

export const useFileHandling = () => {
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

    return {
        file,
        fileInput,
        handleDrop,
        deleteFile,
        openFileInput,
        handleDragOver,
        urlArchivoAnexo,
        tipoMine,
        sizeArchivo,
        handleFileChange,
        nameArchivoAnexo,
    };
};
