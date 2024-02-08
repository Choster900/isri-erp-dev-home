export const usePermissions = (menu, currentUrl) => {
    // Función para obtener los permisos
    const getPermissions = () => {
        // Permisos por defecto
        const defaultPermissions = {
            insertar: 0,
            actualizar: 0,
            eliminar: 0,
            ejecutar: 0,
        };

        // Bucar el submenu que contiene la URL actual
        const foundSubmenu = menu.urls.find(value =>
            value.submenu.some(value2 => value2.url === currentUrl)
        );

        if (foundSubmenu) {
            // Extrae permisos específicos del submenu
            const { insertar, actualizar, eliminar, ejecutar } = foundSubmenu.submenu.find(value2 => value2.url === currentUrl) || {};

            // Retorna permisos específicos o los permisos por defecto si no se encuentran
            return {
                insertar:   insertar    || defaultPermissions.insertar,
                ejecutar:   ejecutar    || defaultPermissions.ejecutar,
                eliminar:   eliminar    || defaultPermissions.eliminar,
                actualizar: actualizar  || defaultPermissions.actualizar,
            };
        }

        // Si no se encuentra la URL en el menú, retornar los permisos por defecto
        return defaultPermissions;
    };

    // Llamar a la función y retornar los permisos
    return getPermissions();
};
