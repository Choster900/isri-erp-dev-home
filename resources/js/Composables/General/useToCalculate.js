export const useToCalculate = () => {
    // Función para redondear a la alta
    const round2Decimals = (num) => {
        var m = Number((Math.abs(num) * 100).toPrecision(15));

        return ( Math.round(m) / 100 * Math.sign(num)).toFixed(2);
    };

    //Funcion para redondear a la baja
    const downwardRounding = (amount) => {
        // Convertimos a un número de 4 decimales
        const number = parseFloat(amount.toFixed(4));

        // Obtenemos la parte fraccionaria
        const fractionalPart = number - Math.floor(number);

        // Convertimos la parte fraccionaria a una cadena
        const fractionalStr = fractionalPart.toFixed(4).substring(2); // Obtenemos los decimales como cadena

        // Obtenemos los terceros y cuartos decimales
        const thirdAndFourthDecimals = parseInt(fractionalStr.substring(2, 4), 10);

        // Verificamos si los decimales tercero y cuarto son mayores a 50
        if (thirdAndFourthDecimals > 50) {
            return (Math.ceil(number * 100) / 100).toFixed(2); // Redondea al alza
        } else {
            return (Math.floor(number * 100) / 100).toFixed(2); // Redondea a la baja
        }
    };

    // Llamar a la función y retornar los permisos
    return {
        round2Decimals, downwardRounding
    }
};