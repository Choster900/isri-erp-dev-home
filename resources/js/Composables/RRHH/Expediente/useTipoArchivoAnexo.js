import axios from "axios";
import { onMounted, ref } from "vue";

/**
 * Hook personalizado para gestionar tipos de archivos de anexos.
 * @returns Objeto con datos y funciones relacionadas con tipos de archivos de anexos.
 */
export const useTipoArchivoAnexo = () => {
    // Estado para almacenar la respuesta de la API
    const objResponse = ref([]);
    // Estado para almacenar los tipos de archivos de anexos filtrados
    const objTipoArchivoAnexo = ref([]);
    // Estado para almacenar una copia sin filtrar de los tipos de archivos de anexos originales
    const objTipoArchivoAnexoOriginal = ref([]);
    // Estado para indicar si se ha activado el filtro en la selección
    const isActiveFilterInSelection = ref(false);

    /**
     * Obtiene todos los tipos de archivos de anexos desde la API.
     * @returns Promesa que se resuelve con la respuesta de la API.
     */
    const getAllTipoArchivoAnexos = async () => {
        try {
            const resp = await axios.get("/getAllTipoArchivoAnexos");
            // Inicializa los estados con la respuesta de la API
            objResponse.value = resp.data;
            objTipoArchivoAnexo.value = resp.data;
            objTipoArchivoAnexoOriginal.value = resp.data;
            return resp;
        } catch (error) {
            throw error;
        }
    };

    // Se ejecuta al montar el componente para obtener los tipos de archivos de anexos
    onMounted(async () => {
        await getAllTipoArchivoAnexos();
    });

    /**
     * Filtra los tipos de archivos de anexos por nombre.
     * @param {string} name - El nombre por el cual filtrar.
     */
    const filterTipoAnexoByName = (name) => {
        if (name === "") {
            if (isActiveFilterInSelection.value) {
                // Si el nombre está vacío y el filtro en la selección está activo, aplica el filtro de la selección
                filterTipoAnexoInSelect(2);
            } else {
                // Si el nombre está vacío y el filtro en la selección no está activo, muestra todos los registros
                objTipoArchivoAnexo.value = objTipoArchivoAnexoOriginal.value;
            }
        } else {
            // Si hay un nombre, filtra por nombre ignorando mayúsculas y minúsculas
            const filteredContracts = objTipoArchivoAnexo.value.filter(
                (index) =>
                    index.nombre_tipo_archivo_anexo
                        .toLowerCase()
                        .includes(name.toLowerCase())
            );
            objTipoArchivoAnexo.value = filteredContracts;
        }
    };

    /**
     * Filtra los tipos de archivos de anexos según la selección.
     * @param {number} tipoFilter - El filtro de tipo a aplicar (1 para todos, 2 para los que tienen archivos).
     */
    const filterTipoAnexoInSelect = (tipoFilter) => {
        // Crea una copia de los datos originales
        const originalData = [...objTipoArchivoAnexoOriginal.value];

        if (tipoFilter == 1) {
            // Si el filtro es 1, muestra todos los registros
            objTipoArchivoAnexo.value = originalData;
            // Desactiva el filtro en la selección y aplica el filtro por nombre
            isActiveFilterInSelection.value = false;
            filterTipoAnexoByName("");
        } else if (tipoFilter == 2) {
            // Si el filtro es 2, filtra por la condición de archivos_anexos.length > 0
            const filteredContracts = originalData.filter(
                (index) => index.archivos_anexos.length > 0
            );
            console.log(filteredContracts);
            objTipoArchivoAnexo.value = filteredContracts;
            // Activa el filtro en la selección
            isActiveFilterInSelection.value = true;
        }
    };

    return {
        objTipoArchivoAnexo,
        filterTipoAnexoByName,
        filterTipoAnexoInSelect,
        getAllTipoArchivoAnexos,
    };
};
