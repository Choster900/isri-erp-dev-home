<template>
    <div id="profile-sidebar"
        class="absolute z-20 top-0 bottom-0 w-full md:w-auto md:static md:top-auto md:bottom-auto -mr-px md:translate-x-0 transition-transform duration-200 ease-in-out"
        :class="profileSidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <div
            class="sticky top-16 bg-white overflow-x-hidden overflow-y-auto no-scrollbar shrink-0 border-r border-slate-200 md:w-72 xl:w-80 h-[calc(100vh-64px)]">

            <!-- Group body -->
            <div class="px-5 py-4">
                <!-- Search form -->
                <form class="relative " v-on:submit.prevent="handleInput">
                    <input v-model="userSearched" @input="handleInput"
                        class="form-input w-full pl-9 text-sm border-slate-300 focus:border-slate-300 rounded-md focus:ring-transparent"
                        type="search" placeholder="Buscar..." />
                    <button class="absolute inset-0 left-auto group" aria-label="Search">
                        <svg class="w-4 h-4 shrink-0 fill-current text-slate-400 group-hover:text-slate-500 ml-3 mr-2"
                            viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M7 14c-3.86 0-7-3.14-7-7s3.14-7 7-7 7 3.14 7 7-3.14 7-7 7zM7 2C4.243 2 2 4.243 2 7s2.243 5 5 5 5-2.243 5-5-2.243-5-5-5z" />
                            <path
                                d="M15.707 14.293L13.314 11.9a8.019 8.019 0 01-1.414 1.414l2.393 2.393a.997.997 0 001.414 0 .999.999 0 000-1.414z" />
                        </svg>
                    </button>
                </form>
                <!-- Team members -->
                <div class="mt-4">

                    <div class="pt-2" v-if="isLoading">
                        <LoaderBar></LoaderBar>
                        <div class="text-center">
                            <span class="text-slate-400">cargando</span>
                        </div>
                    </div>
                    <ul class="mb-6" v-if="userMatches != ''">
                        <li class="-mx-2"
                            :class="{ ' bg-slate-300 rounded-md': isProfileSelected.id_empleado == user.id_empleado }"
                            v-for="user in userMatches" :key="user">
                            <button class="w-full px-2 py-1 rounded " @click="$emit('sendUserDataWhenIsClick', user)"
                                :title="`${user.empleado.codigo_empleado} -${user.pnombre_persona ? user.pnombre_persona : ''} ${user.snombre_persona ? user.snombre_persona : ''} ${user.tnombre_persona ? user.snombre_persona : ''} ${user.papellido_persona ? user.papellido_persona : ''} ${user.sapellido_persona ? user.sapellido_persona : ''} ${user.tapellido_persona ? user.tapellido_persona : ''} `">
                                <div class="flex items-center">
                                    <div class="flex flex-col items-center sm:flex-row sm:justify-between sm:items-end">
                                        <!-- Avatar -->
                                        <div class="contenedor-img">
                                            <img class="rounded-full mr-2 border-2 border-slate-400"
                                                :src="user.fotos != '' ? user.fotos[user.fotos.length - 1].url_foto : 'https://img.freepik.com/free-icon/user_318-159711.jpg?w=2000'" />
                                        </div>
                                    </div>
                                    <div class="truncate">
                                        <span class="text-[9pt] font-medium text-slate-800">
                                            {{ `${user.empleado.codigo_empleado} - ${user.pnombre_persona ?
                                                user.pnombre_persona : ''} ${user.snombre_persona ? user.snombre_persona
                                                    : ''} ${user.tnombre_persona ? user.snombre_persona : ''}
                                                                                        ${user.papellido_persona ? user.papellido_persona : ''}
                                                                                        ${user.sapellido_persona ? user.sapellido_persona : ''}
                                                                                        ${user.tapellido_persona ? user.tapellido_persona : ''} ` }}
                                        </span>
                                    </div>
                                </div>
                            </button>
                        </li>
                    </ul>
                    <div class="text-center" v-else-if="userMatches == '' && !isLoading">
                        <span class="text-slate-400">sin coinsidencias</span>
                    </div>
                </div>
            </div>


        </div>
    </div>
</template>

<script>
import LoaderBar from '@/Components-ISRI/LoaderBar.vue';
export default {
    props: ['isProfileSelected'],
    components: { LoaderBar },
    data() {
        return {
            userSearched: '',// String que contiene el texto de busqueda
            userMatches: [],// Objecto que almacena los usuarios encontrados por el string de busqueda
            isLoading: false,// Manejo de carga de busqueda de datos
            searchTimeout: null,// Manejo de tiempo de ejecucion de peticion
        }
    },
    methods: {
        handleInput() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.userMatches = []
                this.searchingUsers();
            }, 700); // Tiempo de espera de menos de 1 segundo
        },
        async searchingUsers() {
            try {
                this.isLoading = true
                const response = await axios.post('/search-employees', { data: this.userSearched });
                console.log(response);
                this.userMatches = response.data;
            } catch (error) {
                console.log('Error en la búsqueda:', error)
            } finally {
                this.isLoading = false
            }

        },
    },
    mounted() {
        this.searchingUsers()
    },
    watch: {
        userMatches() {
            if (!this.userSearched) {
                this.$emit("sendFirstUserData", this.userMatches[0])
            }
        }
    }
}
</script>
<style scoped>
.contenedor-img {
    position: relative;
    width: 100vw;
    height: 100vh;
    width: 42px;
    height: 36px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.contenedor-img img {
    width: 100%;
    height: 100%;
    border-radius: 100%;
}

.loader {
    width: 8px;
    height: 40px;
    border-radius: 4px;
    display: block;
    background-color: currentColor;
    margin: 20px auto;
    position: relative;
    color: #001c48;
    animation: animloader 0.3s 0.3s linear infinite alternate;
}

.loader::after,
.loader::before {
    content: '';
    width: 8px;
    height: 40px;
    border-radius: 4px;
    background: currentColor;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    left: 20px;
    animation: animloader 0.3s 0.45s linear infinite alternate;
}

.loader::before {
    left: -20px;
    animation-delay: 0s;
}

@keyframes animloader {
    0% {
        height: 48px;
    }

    100% {
        height: 4px;
    }
}
</style>
