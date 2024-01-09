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

        inputToSent.value = validatedInput;
        return inputToSent.value;
    };

    return {
        validateInput,
    };
};

