<template>
    <table class="table-auto w-full editor_listing_table ">
        <thead class="text-xs font-semibold uppercase text-white bg-[#001b47] border-t border-b border-slate-200">
            <tr>
                <th v-for="column in columns" :key="column.name" @click="$emit('sort', column.name)"
                    :class="sortKey === column.name ? (sortOrders[column.name] > 0 ? 'sorting_asc' : 'sorting_desc') : 'sorting'"
                    class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap"
                    :style="'width:' + column.width + ';' + 'cursor:pointer;'">
                    <div class="font-semibold text-center">{{ column.label }}</div>
                </th>
            </tr>
        </thead>
        <thead class="text-xs font-semibold uppercaseborder-t border-b border-slate-200">
            <tr>
                <th v-for="column in columns" :key="column.name"
                    :class="sortKey === column.name ? (sortOrders[column.name] > 0 ? 'sorting_asc' : 'sorting_desc') : 'sorting'"
                    class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap"
                    :style="'width:' + column.width + ';' + 'cursor:pointer;'">
                    <input type="text" class="tabla text-xs font-semibold w-full" @input="sendData($event)" value=""
                        :id="column.name" ref="myInput" v-if="column.name != 'Acciones'">
                </th>
            </tr>
        </thead>
        <slot class=""></slot>
    </table>
</template>

<script>
export default {
    props: ['columns', 'sortKey', 'sortOrders'],
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
table.tablas_sistema thead:nth-child(2) th {
    text-align: center;
    position: sticky;
    top: 0;
    z-index: 10;
}

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
</style>