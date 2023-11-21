export const usePermissions = (menu, currentUrl) => {
    const getPermissions = () => {
        let permits = {
            insertar: 0,
            actualizar: 0,
            eliminar: 0,
            ejecutar: 0,
        };

        menu.urls.forEach((value) => {
            value.submenu.forEach((value2) => {
                if (value2.url === currentUrl) {
                    permits = {
                        insertar: value2.insertar,
                        actualizar: value2.actualizar,
                        eliminar: value2.eliminar,
                        ejecutar: value2.ejecutar,
                    };
                }
            });
        });

        return permits;
    };

    return getPermissions();
};
