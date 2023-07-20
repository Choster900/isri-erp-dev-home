

export const getPermissions = (context) => {
    var URLactual = window.location.pathname
    let data = context.$page.props.menu;
    let menu = JSON.parse(JSON.stringify(data['urls']))
    menu.forEach((value, index) => {
        value.submenu.forEach((value2, index2) => {
            if (value2.url === URLactual) {
                var array = { 'insertar': value2.insertar, 'actualizar': value2.actualizar, 'eliminar': value2.eliminar, 'ejecutar': value2.ejecutar }
                context.permits = array
            }
        })
    })
}