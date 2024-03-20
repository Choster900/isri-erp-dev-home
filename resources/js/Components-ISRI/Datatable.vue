<template>
    <table class="table-auto w-full editor_listing_table ">
        <thead class="text-xs font-semibold uppercase  text-white border-t border-b border-slate-200"
            :class="$page.props.menu.sistema === 'Juridico' ? 'bg-[#3c4557]' : 'bg-[#001c48]'">
            <tr>
                <th v-for="column in columns" :key="column.name" @click="$emit('sort', column.name)"
                    :class="sortKey === column.name ? (sortOrders[column.name] > 0 ? 'sorting_asc' : 'sorting_desc') : 'sorting'"
                    class="px-2 first:pl-5 last:pr-5 py-2" :style="'width:' + column.width + ';' + 'cursor:pointer;'">
                    <div class="font-medium text-center flex justify-center items-center">
                        <p>{{ column.label }}</p>
                        <svg v-if="sortIcons && column.name != 'Acciones'" class="h-[18px] w-[18px] text-white" viewBox="0 0 24 24"
                            fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M5.47719 7.96225C5.1802 8.25099 5.17351 8.72582 5.46225 9.02281C5.75099 9.3198 6.22582 9.32649 6.52281 9.03775L5.47719 7.96225ZM9.6 5H10.35C10.35 4.69857 10.1695 4.42644 9.89188 4.30913C9.61422 4.19182 9.29331 4.25214 9.07719 4.46225L9.6 5ZM8.85 19C8.85 19.4142 9.18579 19.75 9.6 19.75C10.0142 19.75 10.35 19.4142 10.35 19H8.85ZM18.5228 16.0377C18.8198 15.749 18.8265 15.2742 18.5377 14.9772C18.249 14.6802 17.7742 14.6735 17.4772 14.9623L18.5228 16.0377ZM14.4 19H13.65C13.65 19.3014 13.8305 19.5736 14.1081 19.6909C14.3858 19.8082 14.7067 19.7479 14.9228 19.5377L14.4 19ZM15.15 5C15.15 4.58579 14.8142 4.25 14.4 4.25C13.9858 4.25 13.65 4.58579 13.65 5H15.15ZM6.52281 9.03775L10.1228 5.53775L9.07719 4.46225L5.47719 7.96225L6.52281 9.03775ZM8.85 5V19H10.35V5H8.85ZM17.4772 14.9623L13.8772 18.4623L14.9228 19.5377L18.5228 16.0377L17.4772 14.9623ZM15.15 19V5H13.65V19H15.15Z"
                                    fill="currentColor"></path>
                            </g>
                        </svg>
                    </div>
                </th>
            </tr>
        </thead>
        <thead class="border-b border-slate-200">
            <tr>
                <th v-for="column in columns" :key="column.name"
                    :class="sortKey === column.name ? (sortOrders[column.name] > 0 ? 'sorting_asc' : 'sorting_desc') : 'sorting'"
                    class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap"
                    :style="!searchButton ? 'width:' + column.width + ';' + 'cursor:pointer;' : 'width:' + column.width + ';'">

                    <template v-if="column.visible !== undefined ? column.visible : true">
                        <div v-if="column.type != 'select'">
                            <input :type="column.type" class="tabla text-xs font-normal w-full "
                                @input="sendData($event)" @keyup.enter="$emit('execute-search')" value=""
                                :id="column.name" ref="myInput" v-if="column.name != 'Acciones'">
                            <div v-if="column.name == 'Acciones' && searchButton" class="flex justify-center">
                                <button @click="$emit('execute-search')" title="Filtrar por criterios de busqueda">
                                    <svg height="28" width="28" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path
                                            d="M332.998,291.918c52.2-71.895,45.941-173.338-18.834-238.123c-71.736-71.728-188.468-71.728-260.195,0c-71.746,71.745-71.746,188.458,0,260.204c64.775,64.775,166.218,71.034,238.104,18.844l14.222,14.203l40.916-40.916L332.998,291.918z M278.488,278.333c-52.144,52.134-136.699,52.144-188.852,0c-52.152-52.153-52.152-136.717,0-188.861c52.154-52.144,136.708-52.144,188.852,0C330.64,141.616,330.64,226.18,278.488,278.333z" />
                                        <path
                                            d="M109.303,119.216c-27.078,34.788-29.324,82.646-6.756,119.614c2.142,3.489,6.709,4.603,10.208,2.46c3.49-2.142,4.594-6.709,2.462-10.198c-19.387-31.7-17.45-72.962,5.782-102.771c2.526-3.228,1.946-7.898-1.292-10.405C116.48,115.399,111.811,115.979,109.303,119.216z" />
                                        <path
                                            d="M501.499,438.591L363.341,315.178l-47.98,47.98l123.403,138.168c12.548,16.234,35.144,13.848,55.447-6.456C514.505,474.576,517.743,451.138,501.499,438.591z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div v-else>
                            <select @change="sendData($event)" :id="column.name" ref="myInput" class="appearance-none"
                                style="appearance: none; -webkit-appearance: none; -moz-appearance: none;"
                                :style="staticSelect ? 'width: 100px;' : 'width: 100%;'">
                                <option value=""></option>
                                <option v-for="(option, i) in column.options " :key="i" :value="option.value">{{
                option.label }}
                                </option>
                            </select>
                        </div>
                    </template>

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
        inputsToValidate: {
            type: Array,
            default: []
        },
        staticSelect: {
            type: Boolean,
            defeault: true
        },
        sortIcons: {
            type: Boolean,
            default: false
        }
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
            const id = event.target.id;
            // Encuentra el objeto correspondiente en inputsToValidate por inputName
            const inputToValidate = this.inputsToValidate.find(element => element.inputName === id);
            if (inputToValidate) {
                if (inputToValidate.number) {
                    // Reemplazar caracteres no numéricos con una cadena vacía
                    event.target.value = event.target.value.replace(/[^0-9]/g, '');
                }
                if (inputToValidate.limit) {
                    // Limitar la longitud del valor
                    event.target.value = event.target.value.substring(0, inputToValidate.limit);
                }
                if (inputToValidate.amount) {
                    let x = event.target.value.replace(/^\./, '').replace(/[^0-9.]/g, '')
                    event.target.value = x
                    const regex = /^(\d+)?([.]?\d{0,2})?$/
                    if (!regex.test(event.target.value)) {
                        event.target.value = event.target.value.match(regex) || x.substring(0, x.length - 1)
                    }
                }
            }
            const value = event.target.value;
            if (id in this.columnsComputed) {
                this.columnsComputed[id] = value;
            }
            this.$emit('datos-enviados', this.columnsComputed);
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

input.tabla[type="number"] {
    height: 28px;
    line-height: 28px;
    border-radius: 30px;
    padding: 0 8px;
    border: none;
    background-color: #1f355833;
    text-align: center;
}

select {
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
