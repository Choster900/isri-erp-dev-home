<template>
    <div class="mb-4 md:flex flex-row justify-items-center">
        <div class="mx-auto text-center w-[250px] h-[125px] object-contain">
            <img src="../../../img/isri-gob.png" alt="Logo">
        </div>
    </div>
    <div class="mb-4 md:flex flex-row justify-items-center">
        <div class="mx-auto">
            <p class="text-center">Reporte de quedan</p>
        </div>
    </div>
</template>
  
  <script>
  export default {
    props: {
        mensaje: {
            type: String,
            default: '',
        },
    }
  }
  </script>
  
  <style>
  </style>
  