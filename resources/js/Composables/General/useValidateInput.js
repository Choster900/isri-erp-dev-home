import { ref } from "vue";

export const useValidateInput = () => {
    const inputToSent = ref("");
    const validateInput = (input, validation) => {
        let validatedInput = input;

        if (validation.limit) {
            if (input.length > validation.limit) {
                validatedInput = input.substring(0, validation.limit);
            }
        }

        if (validation.upper) {
            validatedInput = validatedInput.toUpperCase();
        }

        if (validation.amount) {
            let x = validatedInput.replace(/^\./, '').replace(/[^0-9.]/g, '')
            validatedInput = x
            const regex = /^(\d+)?([.]?\d{0,2})?$/
            if (!regex.test(validatedInput)) {
                validatedInput = validatedInput.match(regex) || x.substring(0, x.length - 1)
            }
        }

        if (validation.amountx6) {
            let x = validatedInput.replace(/^\./, '').replace(/[^0-9.]/g, '');
            validatedInput = x;
            const regex = /^(\d+)?([.]?\d{0,6})?$/; // Modificado para permitir hasta 6 dígitos después del punto
            if (!regex.test(validatedInput)) {
                validatedInput = validatedInput.match(regex) || x.substring(0, x.length - 1);
            }
        }

        // Si la validación de no empezar con cero está habilitada
        if (validation.noBeginZero) {
            validatedInput = validatedInput.replace(/^0*(?!$)[0-9]+/, '');
        }

        if (validation.number) {
            validatedInput = validatedInput.replace(/[^0-9]/g, '');
        }

        if (validation.numbersCommasAndSpaces) {
            validatedInput = validatedInput.replace(/[^\d, ]/g, '')
        }

        inputToSent.value = validatedInput;
        return inputToSent.value;
    };

    return {
        validateInput,
    };
};

