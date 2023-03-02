<script setup>
import ModalBasicVue from '@/Components-ISRI/AllModal/ModalBasic.vue';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import InputError from "@/Components/InputError.vue";
</script>
<template>
    <div class="m-1.5">

        <ModalBasicVue title="Personas" id="scrollbar-modal" maxWidth="3xl" :modalOpen="scrollbarModalOpen"
            @close-modal-persona="this.$emit('close-definitive')">

            <div class="px-5 py-4">
                <div class="space-y-2">
                    <div id="informacion-personal">
                        <div class="pb-4 mb-4 md:flex flex-row justify-between">
                            <span class="font-semibold text-slate-800 mb-2 text-lg underline underline-offset-2">
                                Información personal
                            </span>
                            <a href="#abajo">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-arrow-narrow-down cursor-pointer" width="30"
                                    height="30" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ff2825" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <title>Ir al final</title>
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <line x1="12" y1="5" x2="12" y2="19" />
                                <line x1="16" y1="15" x2="12" y2="19" />
                                <line x1="8" y1="15" x2="12" y2="19" />
                            </svg>
                            </a>
                        </div>
                        <!--    <pre>

                                    {{ allSelectOptios.locationPersona }}
                                </pre> -->
                        <div class="mb-7 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">

                                <TextInput id="primer-nombre" v-model="persona.pnombre_persona"
                                    :value="persona.pnombre_persona" type="text" placeholder="Primer nombre">
                                    <LabelToInput icon="personalInformation" forLabel="primer-nombre" />
                                </TextInput>
                                <InputError class="mt-2" :message="errosModel.pnombre_persona" />

                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="segundo-nombre" v-model="persona.snombre_persona"
                                    :value="persona.snombre_persona" type="text" placeholder="Segundo nombre">
                                    <LabelToInput icon="personalInformation" forLabel="segundo-nombre" />
                                </TextInput>
                                <InputError class="mt-2" :message="errosModel.snombre_persona" />

                            </div>

                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="tercer-nombre" v-model="persona.tnombre_persona"
                                    :value="persona.tnombre_persona" type="text" placeholder="Tercer nombre (opcional)">
                                    <LabelToInput icon="personalInformation" forLabel="tercer-nombre" />
                                </TextInput>

                            </div>
                        </div>

                        <div class="mb-7 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="primer-apellido" v-model="persona.papellido_persona"
                                    :value="persona.papellido_persona" type="text" placeholder="Primer apellido">
                                    <LabelToInput icon="personalInformation" forLabel="primer-apellido" />
                                </TextInput>
                                <InputError class="mt-2" :message="errosModel.papellido_persona" />

                            </div>

                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="segundo-apellido" v-model="persona.sapellido_persona"
                                    :value="persona.sapellido_persona" type="text" placeholder="Segundo apellido">
                                    <LabelToInput icon="personalInformation" forLabel="segundo-apellido" />
                                </TextInput>
                                <InputError class="mt-2" :message="errosModel.sapellido_persona" />

                            </div>

                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="tercer-apellido" v-model="persona.tapellido_persona"
                                    :value="persona.tapellido_persona" type="text" placeholder="Tercer apellido (opcional)">
                                    <LabelToInput icon="personalInformation" forLabel="tercer-apellido" />
                                </TextInput>

                            </div>
                        </div>

                        <div class="mb-7 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="telefono" v-model="persona.telefono_persona" @change="typePhoneNumber"
                                    :value="persona.telefono_persona" type="text" placeholder="Telefono"
                                    @update:modelValue="typePhoneNumber()">
                                    <LabelToInput icon="personalInformation" forLabel="telefono" />
                                </TextInput>
                                <InputError class="mt-2" :message="errosModel.telefono_persona" />
                            </div>

                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="DUI" v-model="persona.dui_persona" :value="persona.dui_persona" type="text"
                                    placeholder="DUI" @update:modelValue="typeDUI()">
                                    <LabelToInput icon="personalInformation" forLabel="DUI" />
                                </TextInput>
                                <InputError class="mt-2" :message="errosModel.dui_persona" />

                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="Correo" v-model="persona.email_persona" :value="persona.email_persona"
                                    type="email" placeholder="Correo electronico">
                                    <LabelToInput icon="email" forLabel="Correo" />
                                </TextInput>
                                <InputError class="mt-2" :message="errosModel.email_persona" />

                            </div>
                        </div>

                        <div class="mb-7 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Genero <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Genero" v-model="persona.id_genero"
                                        :options="allSelectOptios.gender" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="errosModel.id_genero" />

                            </div>
                        </div>
                    </div>

                    <div id="informacion-familiar">
                        <div class="pb-4 mb-4">
                            <span class="font-semibold text-slate-800 mb-2 text-lg underline underline-offset-2">
                                Información familiar
                            </span>
                        </div>
                        <div class="mb-7 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Estado civil <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold  flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Estado civil" v-model="persona.id_estado_civil"
                                        id="estado-civil" :options="allSelectOptios.civilStatus" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="errosModel.id_estado_civil" />

                            </div>

                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="nombre-conyuge" v-model="persona.nombre_conyuge_persona"
                                    :value="persona.sapellido_persona" type="text" placeholder="Nombre cónyuge">
                                    <LabelToInput icon="personalInformation" forLabel="nombre-conyuge" />
                                </TextInput>
                            </div>

                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="nombre-madre" v-model="persona.nombre_madre_persona"
                                    :value="persona.nombre_madre_persona" type="text"
                                    placeholder="Nombre - madre (completo)">
                                    <LabelToInput icon="personalInformation" forLabel="nombre-madre" />
                                </TextInput>
                            </div>
                        </div>
                        <div class="mb-7 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <TextInput id="nombre-padre" v-model="persona.nombre_padre_persona"
                                    :value="persona.nombre_padre_persona" type="text"
                                    placeholder="Nombre - padre (completo)">
                                    <LabelToInput icon="personalInformation" forLabel="nombre-padre" />
                                </TextInput>
                            </div>
                        </div>
                    </div>

                    <div id="informacion-nacimiento">
                        <div class="pb-4 mb-4">
                            <span class="font-semibold text-slate-800 mb-2 text-lg underline underline-offset-2">
                                Información de nacimiento
                            </span>
                        </div>
                        <div class="mb-7 md:flex flex-row justify-items-start">

                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Municipio <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Municipio" v-model="persona.id_municipio"
                                        :options="allSelectOptios.locationPersona" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="errosModel.id_municipio" />

                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600" for="fecha_nacimiento">
                                    Fecha de nacimiento <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative flex h-8 w-full flex-row-reverse">
                                    <flat-pickr
                                        class="peer w-full text-xs cursor-pointer rounded-r-md border h-8 border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none"
                                        :config="config" v-model="persona.fecha_nac_persona" />
                                    <LabelToInput icon="date" />
                                </div>
                                <InputError class="mt-2" :message="errosModel.fecha_nac_persona" />

                            </div>
                        </div>
                    </div>

                    <div id="abajo">
                        <div class="pb-4 mb-4 md:flex flex-row justify-between">
                            <span class="font-semibold text-slate-800 mb-2 text-lg underline underline-offset-2">
                                Nivel académico
                            </span>
                        </div>
                        <div class="mb-4 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Nivel educativo <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Nivel educativo" v-model="persona.id_nivel_educativo"
                                        :options="allSelectOptios.levelEducation" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="errosModel.id_nivel_educativo" />

                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Nivel profesion <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Nivel profesion" v-model="persona.id_profesion"
                                        :options="allSelectOptios.levelProfession" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="errosModel.id_profesion" />

                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="sticky bottom-0 px-5 py-4 bg-white border-t border-slate-200">
                <div class="flex flex-wrap justify-end space-x-3">

                    <button
                        class="btn-sm border-slate-200 hover:border-slate-300 text-slate-600 underline underline-offset-1"
                        @click.stop="this.$emit('close-definitive')">Cerrar</button>

                    <GeneralButton v-if="infoPersona != ''" @click="updatePerson()"
                        color="bg-orange-700  hover:bg-orange-800" text="Editar persona" icon="add" />
                    <GeneralButton v-else @click="addPerson()" color="bg-green-700  hover:bg-green-800"
                        text="Agregar persona" icon="add" />
                </div>
            </div>

        </ModalBasicVue>
        <!-- End -->
    </div>
</template>

<script>
export default {
    props: {
        scrollbarModalOpen: {
            type: Boolean,
            default: false,
        },
        infoPersona: {
            type: Array,
            default: [],
        },
        align: {
            type: Boolean,
        }
    },
    data: function (props) {
        return {
            allSelectOptios: {
                gender: [],
                civilStatus: [],
                country: [],
                departament: [],
                municipio: [],
                levelEducation: [],
                levelProfession: [],
                locationPersona: [],
            },
            persona: {
                pnombre_persona: '',
                snombre_persona: '',
                tnombre_persona: '',
                papellido_persona: '',
                sapellido_persona: '',
                tapellido_persona: '',
                telefono_persona: '',
                dui_persona: '',
                email_persona: '',
                id_genero: '',
                id_estado_civil: '',
                nombre_conyuge_persona: '',
                nombre_madre_persona: '',
                nombre_padre_persona: '',
                fecha_nac_persona: '',
                id_nivel_educativo: '',
                id_municipio: '',
                id_profesion: '',
                id_persona: '',
            },
            config: {
                altInput: true,
                monthSelectorType: 'static',
                altFormat: "M j, Y",
                dateFormat: "Y-m-d",
                defaultDate: this.infoPersona.fecha_nac_persona,
                prevArrow: '<svg class="fill-current" width="7" height="11" viewBox="0 0 7 11"><path d="M5.4 10.8l1.4-1.4-4-4 4-4L5.4 0 0 5.4z" /></svg>',
                nextArrow: '<svg class="fill-current" width="7" height="11" viewBox="0 0 7 11"><path d="M1.4 10.8L0 9.4l4-4-4-4L1.4 0l5.4 5.4z" /></svg>',
                locale: {
                    firstDayOfWeek: 1,
                    weekdays: {
                        shorthand: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                        longhand: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                    },
                    months: {
                        shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Оct', 'Nov', 'Dic'],
                        longhand: ['Enero', 'Febrero', 'Мarzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                    },
                },
            },
            errosModel: {}
        }
    },
    methods: {
        async listOptionsSelect() {//metodo que trae toda la info de todos los select
            await axios.get("/list-option-select").then((response) => {
                this.allSelectOptios.gender = response.data.gender
                this.allSelectOptios.civilStatus = response.data.civilStatus
                this.allSelectOptios.levelEducation = response.data.levelEducation
                this.allSelectOptios.levelProfession = response.data.levelProfession
                this.allSelectOptios.locationPersona = response.data.location
            });
        },
        typePhoneNumber() {
            var x = this.persona.telefono_persona.replace(/\D/g, '').match(/(\d{0,4})(\d{0,4})/);
            this.persona.telefono_persona = !x[2] ? x[1] : '' + x[1] + '-' + x[2] + (x[4] ? '-' + x[4] : '');
        },
        typeDUI() {
            var x = this.persona.dui_persona.replace(/\D/g, '').match(/(\d{0,8})(\d{0,1})/);
            this.persona.dui_persona = !x[2] ? x[1] : '' + x[1] + '-' + x[2] + (x[4] ? '-' + x[4] : '');
        },
        addPerson() {
            this.$swal.fire({
                title: '¿Esta seguro de guardar los datos?',
                icon: 'question',
                iconHtml: '❓',
                confirmButtonText: 'Si, Guardar',
                confirmButtonColor: '#001b47',
                cancelButtonText: 'Cancelar',
                showCancelButton: true,
                showCloseButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post('/post-persona', this.persona).then((response) => {
                        console.log(response.data);
                        toast.success("El registro se ha agregado correctamente", {
                            autoClose: 5000,
                            position: "top-right",
                            transition: "zoom",
                            toastBackgroundColor: "white",
                        });
                    }).catch((Error) => {
                        if (Error.response.status === 422) {
                            toast.warning("Tienes algunos errores por favor verifica tus datos", {
                                autoClose: 5000,
                                position: "top-right",
                                transition: "zoom",
                                toastBackgroundColor: "white",
                            });
                            let data = Error.response.data.errors
                            var myData = new Object();
                            for (const errorBack in data) {
                                myData[errorBack] = data[errorBack][0]
                            }
                            this.errosModel = myData;
                            setTimeout(() => {
                                this.errosModel = {}
                            }, 9000);
                        }
                    });
                }
            })
        },
        updatePerson() {
            this.$swal.fire({
                title: '¿Esta seguro de editar los datos?',
                icon: 'question',
                iconHtml: '❓',
                confirmButtonText: 'Si, Editar',
                confirmButtonColor: '#D2691E',
                cancelButtonText: 'Cancelar',
                showCancelButton: true,
                showCloseButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post('/update-persona', this.persona).then((response) => {
                        console.log(response.data);
                        toast.success("El registro se edito con exito", {
                            autoClose: 5000,
                            position: "top-right",
                            transition: "zoom",
                            toastBackgroundColor: "white",
                        });
                    }).catch((Error) => {
                        if (Error.response.status === 422) {
                            toast.warning("Tienes algunos errores por favor verifica tus datos", {
                                autoClose: 5000,
                                position: "top-right",
                                transition: "zoom",
                                toastBackgroundColor: "white",
                            });
                            let data = Error.response.data.errors
                            var myData = new Object();
                            for (const errorBack in data) {
                                myData[errorBack] = data[errorBack][0]
                            }
                            this.errosModel = myData;
                            setTimeout(() => {
                                this.errosModel = {}
                            }, 9000);
                        }
                    });
                }
            })
        },


    },
    created() {
        this.listOptionsSelect()
    },
    watch: {
        infoPersona: function (value, oldvalue) {
            this.persona.pnombre_persona = this.infoPersona.pnombre_persona;
            this.persona.snombre_persona = this.infoPersona.snombre_persona;
            this.persona.tnombre_persona = this.infoPersona.tnombre_persona;
            this.persona.papellido_persona = this.infoPersona.papellido_persona;
            this.persona.sapellido_persona = this.infoPersona.sapellido_persona;
            this.persona.tapellido_persona = this.infoPersona.tapellido_persona;
            this.persona.telefono_persona = this.infoPersona.telefono_persona;
            this.persona.dui_persona = this.infoPersona.dui_persona;
            this.persona.email_persona = this.infoPersona.email_persona;
            this.persona.id_genero = this.infoPersona.id_genero;
            this.persona.id_estado_civil = this.infoPersona.id_estado_civil;
            this.persona.id_estado_civil = this.infoPersona.id_estado_civil;
            this.persona.nombre_conyuge_persona = this.infoPersona.nombre_conyuge_persona;
            this.persona.nombre_padre_persona = this.infoPersona.nombre_padre_persona;
            this.persona.fecha_nac_persona = this.infoPersona.fecha_nac_persona;
            this.persona.id_nivel_educativo = this.infoPersona.id_nivel_educativo;
            this.persona.id_municipio = this.infoPersona.id_municipio;
            this.persona.id_profesion = this.infoPersona.id_profesion;
            this.persona.id_persona = this.infoPersona.id_persona;



        }
    }
}
</script>

<style></style>