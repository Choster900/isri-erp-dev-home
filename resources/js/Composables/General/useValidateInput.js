import { ref } from "vue";

export const useValidateInput = () => {
    const inputToSent = ref(null)
    const validateInput = (input, validation) => {
        if (validation.limit) {
            if (input.length > validation.limit) {
                inputToSent.value = input.substring(0, validation.limit);
            }else{
                inputToSent.value = input
            }
        }
        return inputToSent.value
    };

    return {
        validateInput,
    };
};
