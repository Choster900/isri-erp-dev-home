<template>
    <div class="custom-select">
        <div class="select-container">
            <input v-model="searchTerm" @input="handleInput" placeholder="Buscar..." class="search-input" v-if="isAsync" />
            <select v-model="selectedOption" @input="handleOptionSelect" class="styled-select">
                <option disabled value="">{{ isLoading ? 'Cargando...' : 'Selecciona una opción' }}</option>
                <option v-for="option in options" :value="option.id" :key="option.id">
                    {{ option.name }}
                </option>
            </select>
        </div>
        <div v-if="isLoading" class="spinner">
            <!-- Spinner personalizado con CSS -->
            <div class="spinner-circle"></div>
        </div>
        <pre>{{ options }}</pre>
    </div>
</template>
  
<script>
import { ref, toRefs } from 'vue';
import axios from 'axios';
import _ from "lodash";

export default {
    name: 'Select2',
    props: {
        staticOptions: {
            type: String,
            default: ''
        },
        asyncUrl: {
            type: String,
            default: 'text'
        },
        isAsync: {
            type: Boolean,
            default: false
        },
    },
    setup(props) {
        const searchTerm = ref('');
        const options = ref([]);
        const selectedOption = ref(null);
        const isLoading = ref(false);

        const { isAsync, asyncUrl, staticOptions } = toRefs(props)

        const fetchOptions = _.debounce(async () => {
            isLoading.value = true;
            try {
                const response = await axios.post(asyncUrl.value, {
                    busqueda: searchTerm.value,
                });
                options.value = response.data.employees;
            } catch (error) {
                console.error('Error fetching options:', error);
            } finally {
                isLoading.value = false;
            }
        }, 350); // Aquí puedes ajustar el tiempo de espera

        function handleInput() {
            if (isAsync) {
                fetchOptions();
            } else {
                options.value = staticOptions.value
            }
        }

        return {
            searchTerm,
            options,
            selectedOption,
            handleInput,
            isLoading,
        };
    },
};
</script>
  
<style>
/* Estilos para el spinner y la disposición del select */
.custom-select {
  position: relative;
  display: inline-block;
}

.select-container {
  position: relative;
  display: inline-block;
}

.styled-select {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  padding: 8px 30px 8px 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 16px;
  cursor: pointer;
  width: 200px; /* Ajusta el ancho según tu diseño */
  background-color: white;
}

.styled-select:focus {
  outline: none;
  border-color: #3498db;
}

.search-input {
  position: absolute;
  top: 0;
  left: 0;
  border: 1px solid #ccc;
  border-radius: 4px;
  padding: 8px;
  font-size: 16px;
  width: calc(100% - 40px); /* Ajusta el ancho según tu diseño */
}

.spinner {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-top: 10px;
}

.spinner-circle {
  border: 4px solid #ccc;
  border-top: 4px solid #3498db;
  border-radius: 50%;
  width: 30px;
  height: 30px;
  animation: spin 1s linear infinite;
  /* Animación para girar */
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }

  100% {
    transform: rotate(360deg);
  }
}
</style>
  