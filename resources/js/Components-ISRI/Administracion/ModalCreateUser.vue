<script setup>
import Modal from "@/Components/Modal.vue";
import ModalVue from "@/Components-ISRI/AllModal/BasicModal.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import axios from 'axios';
import moment from 'moment';
</script>

<template>

<ModalVue :show="showModalCreate" @close-modal="closeModal()" title="Creacion de Usuario "
  @close="closeModal()">
  <div class="px-3 pt-1 pb-1">
    <div class="text-sm">
      <div class="md:flex flex-row justify-center">
        <span class="font-semibold text-slate-800 mb-2 text-lg">Ingresa número de DUI</span>
      </div>
      <!-- <div class="mb-2 justify-center">Ingresa número de DUI</div> -->
      <div class="mb-2 md:flex flex-row justify-center">
        <div class="md:mr-2 md:mb-0 basis-1/2">       
          <TextInput v-bind:readOnly="!modalDataCreate.disable_submit" v-model="modalDataCreate.dui" @update:modelValue="typeDUI()" :label-input="false" id="personal-information" type="text"
            placeholder="DUI">
            <LabelToInput icon="personalInformation" forLabel="personal-information" />
          </TextInput>
        </div>
      </div>
      <div class=" md:flex flex-row justify-center">
          <div class="md:mr-2 md:mb-0 px-1">
            <GeneralButton v-bind:disabled="!modalDataCreate.disable_submit" @click="searchDui()" color="bg-blue-700  hover:bg-blue-800" text="Buscar" icon="add" />
          </div>
          <div class="md:mr-2 md:mb-0 px-1">
            <GeneralButton v-bind:disabled="modalDataCreate.disable_submit" text="Limpiar" icon="add" @click="cleanModalCreateInputs()" />
          </div>
      </div>
      <span class="font-semibold text-slate-800 mb-4 text-lg">Informacion de la persona:</span>
      <!-- <div class="mb-2">Informacion de la persona:</div> -->
      <div class="mb-4 md:flex flex-row justify-items-start">
          <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
            <TextInput v-model="modalDataCreate.nombre_persona" v-bind:readOnly="true" id="personal-information" type="text"
            placeholder="Nombre">
            <LabelToInput icon="personalInformation" forLabel="personal-information" />
            </TextInput>
          </div>
          <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
            <TextInput v-model="modalDataCreate.fecha_nacimiento" v-bind:readOnly="true" id="personal-information" type="text"
            placeholder="Fecha de nacimiento">
            <LabelToInput icon="personalInformation" forLabel="personal-information" />
            </TextInput>
          </div>
        </div>

        <div>
          <span class="font-semibold text-slate-800 mb-4 text-lg">Selecciona un sistema y un rol:</span>
        </div>
        <!-- <div class="mb-2">Selecciona un sistema y un rol:</div> -->
        <div class="mb-4 md:flex flex-row justify-items-start">
          <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
            <label class="block mb-2 text-xs font-light text-gray-600">
              Sistema <span class="text-red-600 font-extrabold">*</span>
            </label>
            <div class="relative font-semibold  flex h-8 w-full flex-row-reverse ">
              <Multiselect v-bind:disabled="modalDataCreate.disable_submit" v-model="modalDataCreate.id_sistema" :options="modalDataCreate.systems" @select="getRolesPerSystem()"
                placeholder="Sistema" :searchable="true" />
              <LabelToInput icon="list" />
            </div>
          </div>
          <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
            <label class="block mb-2 text-xs font-light text-gray-600">
              Rol <span class="text-red-600 font-extrabold">*</span>
            </label>
            <div class="relative font-semibold  flex h-8 w-full flex-row-reverse ">
              <Multiselect v-bind:disabled="modalDataCreate.disable_submit" v-model="modalDataCreate.id_role" :options="modalDataCreate.roles" placeholder="Rol"
                :searchable="true" />
              <LabelToInput icon="list" />
            </div>
          </div>
        </div>

        <div>
          <span class="font-semibold text-slate-800 mb-4 text-lg">Ingresa nombre de usuario y contraseña</span>
        </div>
        <!-- <div class="mb-2 justify-center">Ingresa nombre de usuario y contraseña</div> -->
        <div class="mb-4 md:flex flex-row justify-items-start">
          <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
            <TextInput v-bind:readOnly="modalDataCreate.disable_submit" v-model="modalDataCreate.nick_usuario" id="personal-information" type="text"
            placeholder="Nombre Usuario">
            <LabelToInput icon="personalInformation" forLabel="personal-information" />
            </TextInput>
          </div>
          <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
            <TextInput v-bind:readOnly="modalDataCreate.disable_submit" v-model="modalDataCreate.password" id="personal-information" type="password"
            placeholder="Contraseña">
            <LabelToInput icon="password" forLabel="personal-information" />
            </TextInput>
          </div>
        </div>
        
        <div class="mb-4 md:flex flex-row justify-center">
          <div class="mb-4 md:mr-2 md:mb-0 px-1">
            <GeneralButton v-bind:disabled="modalDataCreate.disable_submit" @click="saveNewUser()" color="bg-green-700  hover:bg-green-800" text="Guardar" icon="add" />
          </div>
          <div class="mb-4 md:mr-2 md:mb-0 px-1">
            <GeneralButton text="Cancelar" icon="add" @click="closeModal()" />
          </div>
        </div>

        <div class="text-xs text-slate-500">ISRI2023</div>
      </div>
    </div>
  </ModalVue>
  
</template>

<script>
export default {
    props: ["showModalCreate", "modalDataCreate"],
    methods:{
      closeModal(){
        this.cleanModalCreateInputs()
        this.modalDataCreate.dui=''
        this.$emit("cerrar-modal")
      },
      cleanModalCreateInputs() {
      this.modalDataCreate.id_persona = ''
      this.modalDataCreate.nick_usuario = ''
      this.modalDataCreate.password = ''
      this.modalDataCreate.nombre_persona = ''
      this.modalDataCreate.fecha_nacimiento = ''
      this.modalDataCreate.disable_submit=true
      this.modalDataCreate.id_sistema=''
      this.modalDataCreate.id_role=''
      this.modalDataCreate.roles=[]
    },
      saveNewUser(){
        let someError = false;
        let errors='Errores: ';
        if(this.modalDataCreate.nick_usuario==""){
          errors = errors + '<br>Debe escribir nombre de usuario.'
          someError=true
        }
        if(this.modalDataCreate.password==""){
          errors = errors + '<br>Debe escribir una contraseña.'
          someError=true
        }
        if(this.modalDataCreate.id_sistema==""){
          errors = errors + '<br>Debe seleccionar sistema.'
          someError=true
        }
        if(this.modalDataCreate.id_role==""){
          errors = errors + '<br>Debe seleccionar rol.'
          someError=true
        }
        if(someError){
          this.$swal.fire({
          title: 'Información incompleta',
          html: errors,
          icon: 'warning',
          })
        }else{
          this.$swal.fire({
            title: 'Esta seguro de guardar el usuario',
            icon: 'question',
            iconHtml: '✅',
            confirmButtonText: 'Si, Guardar',
            confirmButtonColor: '#15803D',
            cancelButtonText: 'Cancelar',
            showCancelButton: true,
            showCloseButton: true
          }).then((result) => {
            if (result.isConfirmed) {
              axios.post("/save-user", {
                id_persona: this.modalDataCreate.id_persona,
                nick_usuario: this.modalDataCreate.nick_usuario,
                password : this.modalDataCreate.password,
                id_role : this.modalDataCreate.id_role
              }).then((response) => {
                this.$swal.fire({
                  text: response.data.mensaje,
                  icon: 'success',
                  timer: 3000
                })
                this.cleanModalCreateInputs()
                this.$emit("update-table")
                this.$emit("cerrar-modal")
                this.modalDataCreate.dui=''
              }).catch((errors) => {
                  if(errors.response.status===422){
                    console.log(errors);
                    this.$swal.fire({
                    title: 'Error en la petición',
                    html: errors.response.data,
                    icon: 'warning',
                  })
                  }else{
                    let msg = this.manageError(errors)
                    this.$swal.fire({
                      title: 'Operación cancelada',
                      text: msg,
                      icon: 'warning',
                      timer:5000
                    })
                  }
                })
            }
          })
        }
      },
      getRolesPerSystem(){
        axios.get('/roles-per-system', { params: this.modalDataCreate })
        .then((response) => {
          this.modalDataCreate.roles = response.data.roles
          })
        .catch((errors) => {
          let msg = this.manageError(errors)
          this.$swal.fire({
            title: 'Operación cancelada',
            text: msg,
            icon: 'warning',
            timer:5000
          })
        })
      },
      searchDui(){
        if(this.modalDataCreate.dui==""){
            this.$swal.fire({
            title: 'Información incompleta',
            text: 'Digite numero de DUI',
            icon: 'warning',
            timer: 5000
            })
        }else{
            this.cleanModalCreateInputs()
            axios.get('/get-dui', { params: this.modalDataCreate })
            .then((response) => {
                let persona = response.data.persona
                let usuario = response.data.usuario
                if(persona===""){
                    this.$swal.fire({
                    title: 'Persona no encontrada',
                    text: 'Este DUI no se encuentra en nuestros registros.',
                    icon: 'warning',
                    timer: 3000
                    })
                }else{
                  if(usuario===""){
                    this.modalDataCreate.disable_submit=false
                    this.modalDataCreate.nombre_persona=persona.nombre_persona
                    this.modalDataCreate.id_persona=persona.id_persona
                    this.modalDataCreate.fecha_nacimiento=moment(persona.fecha_nac_persona).format('dddd Do MMMM YYYY')
                  }else{
                    let status;
                    usuario.estado_usuario == 1 ? status='Activo' : status='Inactivo'
                    this.$swal.fire({
                    title: 'Persona ya posee usuario.',
                    html: '<br>Nombre: '+persona.nombre_persona+'<br>Usuario : '+usuario.nick_usuario+' <br> Estado : '+status,
                    icon: 'warning',
                    })
                  }
                }
            })
            .catch((errors) => {
              let msg = this.manageError(errors)
              this.$swal.fire({
                title: 'Operación cancelada',
                text: msg,
                icon: 'warning',
                timer:5000
              })
            })
        }
      },
      typeDUI() {
        var x = this.modalDataCreate.dui.replace(/\D/g, '').match(/(\d{0,8})(\d{0,1})/);
        this.modalDataCreate.dui = !x[2] ? x[1] : '' + x[1] + '-' + x[2] + (x[4] ? '-' + x[4] : '');
      },
    }
}
</script>

<style>

</style>