import { ref } from "vue";

export const useFileHandling = () => {
    const fileInput = ref(null);
    const urlArchivoAnexo = ref(null);
    const nameArchivoAnexo = ref(null);
    const openFileInput = () => {
        fileInput.value.click();
    };

    const handleFileChange = () => {
        const selectedFile = fileInput.value.files[0];
        console.log(selectedFile);
        nameArchivoAnexo.value = selectedFile.name
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
        nameArchivoAnexo.value = selectedFile.name
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
        console.log("Imagen seleccionada:", selectedFile.name);
    };

    const setPdfData = (selectedFile) => {
        urlArchivoAnexo.value = URL.createObjectURL(selectedFile);
        console.log("PDF seleccionado:", selectedFile.name);
    };

    const deleteFile = () => {
        fileInput.value = null;
        urlArchivoAnexo.value = null;
        nameArchivoAnexo.value = null;
        console.log("Archivo eliminado.");
    }

    return {
        fileInput,
        urlArchivoAnexo,
        handleDrop,
        openFileInput,
        handleDragOver,
        handleFileChange,
        nameArchivoAnexo,
        deleteFile,
    };
};
