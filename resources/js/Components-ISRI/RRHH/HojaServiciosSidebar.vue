<template>
    <div id="profile-sidebar"
        class="absolute z-20 top-0 bottom-0 w-full md:w-auto md:static md:top-auto md:bottom-auto -mr-px md:translate-x-0 transition-transform duration-200 ease-in-out"
        :class="profileSidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <div
            class="sticky top-16 bg-white overflow-x-hidden overflow-y-auto no-scrollbar shrink-0 border-r border-slate-200 md:w-72 xl:w-80 h-[calc(100vh-64px)]">

            <!-- Profile group -->
            <div>
                <!-- Group header -->
                <div class="sticky top-0 z-10">
                    <div class="flex items-center bg-white border-b border-slate-200 px-5 h-16">
                        <div class="w-full flex items-center justify-between">
                            <!-- Profile image -->
                            <div class="relative">
                                <div class="grow flex items-center truncate">
                                    <img class="w-9 h-10 rounded-full mr-2" src="../../../img/isri-logo2.png" width="32"
                                        height="32" alt="Group 01" />
                                    <div class="truncate">
                                        <span class="font-semibold text-slate-800">ISRI.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Group body -->
                <div class="px-5 py-4">
                    <!-- Search form -->
                    <form class="relative">
                        <label for="profile-search" class="sr-only">Buscar</label>
                        <input id="profile-search" v-model="userSearched" @input="handleInput"
                            class="form-input w-full pl-9 text-sm border-slate-300 focus:border-slate-300 rounded-md focus:ring-transparent"
                            type="search" placeholder="Buscar..." />
                        <button class="absolute inset-0 right-auto group" type="submit" aria-label="Search">
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
                        <div class="text-xs font-semibold text-slate-400 uppercase mb-3">Empleados</div>
                        <ul class="mb-6">
                            <li class="-mx-2" v-for="users in dataResponseUser" :key="users">
                                <button class="w-full p-2 rounded " @click.stop="$emit('close-profilesidebar')"
                                    :title="`${users.empleado.codigo_empleado} -${users.pnombre_persona ? users.pnombre_persona : ''} ${users.snombre_persona ? users.snombre_persona : ''} ${users.tnombre_persona ? users.snombre_persona : ''} ${users.papellido_persona ? users.papellido_persona : ''} ${users.sapellido_persona ? users.sapellido_persona : ''} ${users.tapellido_persona ? users.tapellido_persona : ''} `">
                                    <div class="flex items-center">
                                        <div class="relative mr-2">
                                            <img class="w-8 h-8 rounded-full"
                                                src="https://img.freepik.com/foto-gratis/viejo-fondo-negro-textura-grunge-papel-tapiz-oscuro-pizarra-pizarra-pared-habitacion_1258-28313.jpg"
                                                width="32" height="32" alt="User 08" />
                                        </div>
                                        <div class="truncate">
                                            <span class="text-sm font-medium text-slate-800" >
                                                {{ `${users.empleado.codigo_empleado} - ${users.pnombre_persona ?
                                                    users.pnombre_persona : ''} ${users.snombre_persona ? users.snombre_persona : ''} ${users.tnombre_persona ? users.snombre_persona : ''}
                                                    ${users.papellido_persona ? users.papellido_persona : ''} ${users.sapellido_persona ? users.sapellido_persona : ''}
                                                     ${users.tapellido_persona ? users.tapellido_persona : ''} ` }}
                                            </span>
                                        </div>
                                    </div>
                                </button>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>
  
<script>
export default {
    name: 'ProfileSidebar',
    props: ['profileSidebarOpen'],

    data() {
        return {
            userSearched: '',
            dataResponseUser: [],
            isLoadingToSearch: false,
            searchTimeout: null,
        }
    },
    methods: {
        handleInput() {
            this.dataResponseUser = []
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.searchingUsers();
            }, 1000); // Tiempo de espera de 1 segundo
        },
        async searchingUsers() {
            if (this.userSearched !== '') {
                try {
                    this.isLoadingToSearch = true
                    const response = await axios.post('/search-employees', { data: this.userSearched });
                    console.log(response.data);
                    this.dataResponseUser = response.data;
                } catch (error) {
                    console.log('Error en la búsqueda:', error)
                } finally {
                    this.isLoadingToSearch = false
                    this.loading = false
                }
            }
        },
    }
}
</script>