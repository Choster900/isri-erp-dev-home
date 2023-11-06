import axios from "axios";
import { ref } from "vue";
import _ from "lodash";

export const usePersona = () => {
    const isLoadingEmployee = ref(false);

    const getPeopleByName = async (nombreToSearch) => {
        //console.log(user.value.userId);

        try {
            const resp = await axios.post("/getPersonaByName", {
                nombre: nombreToSearch,
            });

            return resp.data;
        } catch (error) {
            //   handleError(error);
            console.error(error);
            //reject(error);
        }
    };

    return {
        getPeopleByName,
    };
};
