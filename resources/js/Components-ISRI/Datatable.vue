<template>
    <table class="table-auto w-full">
        <thead class="text-xs font-semibold uppercase text-slate-500 bg-slate-50 border-t border-b border-slate-200">
            <tr>
                <th v-for="column in columns" :key="column.name" @click="$emit('sort', column.name)"
                    :class="sortKey === column.name ? (sortOrders[column.name] > 0 ? 'sorting_asc' : 'sorting_desc') : 'sorting'"
                    class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap"
                    :style="'width:' + column.width + ';' + 'cursor:pointer;'">
                    <div class="font-semibold text-left">{{ column.label }}</div>
                </th>
            </tr>
        </thead>
        <slot></slot>
    </table>
</template>

<script>
export default {
    props: ['columns', 'sortKey', 'sortOrders']
}
</script>