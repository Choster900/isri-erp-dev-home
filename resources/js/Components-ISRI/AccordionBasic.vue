<template>
  <div class="px-5 py-4 rounded-sm border border-slate-200">
    <button class="flex items-center justify-between w-full group mb-1" @click.prevent="toggleAccordion"
      :aria-expanded="open">
      <div class="text-sm text-slate-800 font-medium">{{ title }}</div>
      <svg class="w-8 h-8 shrink-0 fill-current text-slate-400 group-hover:text-slate-500 ml-3"
        :class="{ 'rotate-180': open }" viewBox="0 0 32 32">
        <path d="M16 20l-5.4-5.4 1.4-1.4 4 4 4-4 1.4 1.4z" />
      </svg>
    </button>
    <div class="text-sm accordion-content" :class="{ 'open': open }" ref="accordionContent">
      <slot />
    </div>
  </div>
</template>

<script>
import { ref } from 'vue'

export default {
  name: 'AccordionBasic',
  props: ['title'],
  setup() {
    const open = ref(false)
    const accordionContent = ref(null)

    const toggleAccordion = () => {
      open.value = !open.value

      // Ajusta la altura máxima para lograr la transición suave
      if (open.value) {
        accordionContent.value.style.maxHeight = accordionContent.value.scrollHeight + 'px'
      } else {
        accordionContent.value.style.maxHeight = '0'
      }
    }

    return {
      open,
      toggleAccordion,
      accordionContent,
    }
  },
}
</script>

<style scoped>
.accordion-content {
  overflow: hidden;
  transition: max-height 0.3s ease-in-out, opacity 0.3s ease-in-out;
  max-height: 0;
  opacity: 0;
}

.accordion-content.open {
  max-height: auto;
  /* Ajusta este valor según sea necesario */
  opacity: 1;
}
</style>
  