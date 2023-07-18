<template>
    <table class="table-auto w-full editor_listing_table ">
        <thead class="text-xs font-semibold uppercase  text-white bg-[#001c48] border-t border-b border-slate-200">
            <tr>
                <th v-for="column in columns" :key="column.name" @click="$emit('sort', column.name)"
                    :class="sortKey === column.name ? (sortOrders[column.name] > 0 ? 'sorting_asc' : 'sorting_desc') : 'sorting'"
                    class="px-2 first:pl-5 last:pr-5 py-2" :style="'width:' + column.width + ';' + 'cursor:pointer;'">
                    <div class="font-medium text-center">{{ column.label }}</div>
                </th>
            </tr>
        </thead>
        <thead class="border-b border-slate-200">
            <tr>
                <th v-for="column in columns" :key="column.name"
                    :class="sortKey === column.name ? (sortOrders[column.name] > 0 ? 'sorting_asc' : 'sorting_desc') : 'sorting'"
                    class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap"
                    :style="!searchButton ? 'width:' + column.width + ';' + 'cursor:pointer;' : 'width:' + column.width + ';'">

                    <div v-if="column.type != 'select'">
                        <input :type="column.type" class="tabla text-xs font-normal w-full " @input="sendData($event)"
                            @keyup.enter="$emit('execute-search')" value="" :id="column.name" ref="myInput"
                            v-if="column.name != 'Acciones'">
                        <div v-if="column.name == 'Acciones' && searchButton" class="flex justify-center">
                            <button @click="$emit('execute-search')" title="Filtrar por criterios de busqueda">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div v-else>
                        <select @change="sendData($event)" :id="column.name" ref="myInput" class="appearance-none"
                            style="appearance: none; -webkit-appearance: none; -moz-appearance: none;">
                            <option value=""></option>
                            <option v-for="(option, i) in column.options " :key="i" :value="option.value">{{ option.label }}
                            </option>
                        </select>
                    </div>

                </th>
            </tr>
        </thead>
        <slot class=""></slot>
    </table>
</template>

<script>
export default {
    props: {
        columns: {
            type: Array,
            default: [],
        },
        sortKey: {
            type: String,
            default: ''
        },
        sortOrders: {
            type: Object,
            default: []
        },
        searchButton: {
            type: Boolean,
            default: false
        },
    },
    data: function () {
        return {
            dataSend: {},
            columnsWhioutAcciones: {}
        }
    },
    computed: {
        columnsComputed() {
            const newObj = Object.keys(this.columns).map(key => ({
                [this.columns[key].name]: ''
            })).reduce((result, current) => Object.assign(result, current), {});
            return newObj
        },
    },
    methods: {
        sendData(event) {
            const value = event.target.value;
            const id = event.target.id;
            if (id in this.columnsComputed) {
                this.columnsComputed[id] = value;
            }
            this.$emit('datos-enviados', this.columnsComputed)
        }
    },


}
</script>
<style scoped>
/*estilo inputs de filtro en tabla*/
input.tabla[type="text"] {
    height: 28px;
    line-height: 28px;
    border-radius: 25px;
    padding: 0 8px;
    border: none;
    background-color: #1f355833;
    text-align: center;
}

input.tabla[type="date"] {
    height: 28px;
    line-height: 28px;
    border-radius: 30px;
    padding: 0 8px;
    border: none;
    background-color: #1f355833;
    text-align: center;
}

select {
    width: 100px;
    height: 28px;
    line-height: 28px;
    border-radius: 30px;
    padding: 0 8px;
    border: none;
    background-color: #1f355833;
    text-align: center;
    font-size: 9pt;
    appearance: none;
}
</style>