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
                    :style="!searchButton ? 'width:' + column.width + ';' + 'cursor:pointer;' : 'width:' + column.width + ';'"
                    >

                    <div v-if="column.type != 'select'">
                        <input :type="column.type" class="tabla text-xs font-normal w-full " @input="sendData($event)"
                            value="" :id="column.name" ref="myInput" v-if="column.name != 'Acciones'">
                        <div v-if="column.name == 'Acciones' && searchButton" class="flex justify-center">
                            <button @click="$emit('execute-search')" title="Filtrar por criterios de busqueda">
                                <svg viewBox="0 0 600 600" version="1.1" id="svg9724" sodipodi:docname="search.svg"
                                inkscape:version="1.2.2 (1:1.2.2+202212051550+b0a8486541)" width="22px" height="22px"
                                xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
                                xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
                                xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg" fill="#000000">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <defs id="defs9728"></defs>
                                    <sodipodi:namedview id="namedview9726" pagecolor="#ffffff" bordercolor="#666666"
                                        borderopacity="1.0" inkscape:showpageshadow="2" inkscape:pageopacity="0.0"
                                        inkscape:pagecheckerboard="0" inkscape:deskcolor="#d1d1d1" showgrid="true"
                                        inkscape:zoom="0.84118632" inkscape:cx="29.125533" inkscape:cy="385.76471"
                                        inkscape:window-width="1920" inkscape:window-height="1009" inkscape:window-x="0"
                                        inkscape:window-y="1080" inkscape:window-maximized="1"
                                        inkscape:current-layer="g10449" showguides="true">
                                        <inkscape:grid type="xygrid" id="grid9972" originx="0" originy="0"></inkscape:grid>
                                        <sodipodi:guide position="-260,300" orientation="0,-1" id="guide383"
                                            inkscape:locked="false"></sodipodi:guide>
                                        <sodipodi:guide position="300,520" orientation="1,0" id="guide385"
                                            inkscape:locked="false">
                                        </sodipodi:guide>
                                        <sodipodi:guide position="240,520" orientation="0,-1" id="guide939"
                                            inkscape:locked="false">
                                        </sodipodi:guide>
                                        <sodipodi:guide position="220,80" orientation="0,-1" id="guide941"
                                            inkscape:locked="false">
                                        </sodipodi:guide>
                                    </sodipodi:namedview>
                                    <g id="g10449" transform="matrix(0.95173205,0,0,0.95115787,13.901174,12.168794)"
                                        style="stroke-width:1.05103">
                                        <path
                                            style="color:#000000;fill:#000000;stroke-linecap:round;stroke-linejoin:round;-inkscape-stroke:none;paint-order:stroke fill markers"
                                            d="m 206.04492,-12.792969 c -121.380675,0 -220.650389,99.36284 -220.650389,220.783199 0,121.42037 99.269714,220.78321 220.650389,220.78321 121.38068,0 220.65039,-99.36284 220.65039,-220.78321 0,-121.420359 -99.26971,-220.783199 -220.65039,-220.783199 z m 0,84.082031 c 75.90523,10e-7 136.56641,60.688518 136.56641,136.701168 0,76.01266 -60.66118,136.70118 -136.56641,136.70118 -75.90522,0 -136.568357,-60.68852 -136.568358,-136.70118 10e-7,-76.01265 60.663138,-136.701168 136.568358,-136.701168 z"
                                            id="path397"></path>
                                        <g id="path10026" inkscape:transform-center-x="-0.59233046"
                                            inkscape:transform-center-y="-20.347403"
                                            transform="matrix(1.3807551,0,0,1.2700888,273.60014,263.99768)"></g>
                                        <g id="g11314" transform="matrix(1.5092301,0,0,1.3955555,36.774048,-9.4503933)"
                                            style="stroke-width:50.6951"></g>
                                        <path style="color:#000000;fill:#000000;stroke-linecap:round;-inkscape-stroke:none"
                                            d="m 332.15625,292.14648 a 42.041302,42.041302 0 0 0 -29.73242,12.30469 42.041302,42.041302 0 0 0 -0.0176,59.45508 l 241.63867,241.78711 a 42.041302,42.041302 0 0 0 59.45508,0.0176 42.041302,42.041302 0 0 0 0.0195,-59.45508 L 361.87891,304.4707 a 42.041302,42.041302 0 0 0 -29.72266,-12.32422 z"
                                            id="path453"></path>
                                        <path
                                            style="color:#000000;fill:#000000;stroke-linecap:round;stroke-linejoin:round;-inkscape-stroke:none;paint-order:stroke fill markers"
                                            d="m 300.47656,-223.75195 a 10.51035,10.51035 0 0 0 -10.51172,10.50976 c 0,27.82165 -12.93301,54.03401 -35.0039,70.94922 -22.07089,16.91522 -50.72689,22.58021 -77.56641,15.33399 a 10.51035,10.51035 0 0 0 -12.88672,7.40625 10.51035,10.51035 0 0 0 7.40821,12.88671 c 33.14139,8.947634 68.5821,1.9411 95.83203,-18.94336 27.24993,-20.88445 43.23828,-53.29099 43.23828,-87.63281 a 10.51035,10.51035 0 0 0 -10.50977,-10.50976 z"
                                            id="path1009" transform="scale(1,-1)"></path>
                                    </g>
                                </g>
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
            type: String,
            default: ''
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
}</style>