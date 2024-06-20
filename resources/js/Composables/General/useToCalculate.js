export const useToCalculate = () => {
    // Función para redondear
    const round2Decimals = (num) => {
        var m = Number((Math.abs(num) * 100).toPrecision(15));

        return Math.round(m) / 100 * Math.sign(num);
    };

    // Llamar a la función y retornar los permisos
    return {
        round2Decimals
    }
};