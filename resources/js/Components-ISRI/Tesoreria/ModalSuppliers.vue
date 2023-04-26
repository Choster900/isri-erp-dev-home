<script setup>
import ModalBasicVue from '@/Components-ISRI/AllModal/ModalBasic.vue';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import InputError from "@/Components/InputError.vue";
import moment from 'moment';

</script>
<template>
    <div class="m-1.5">
        <ModalBasicVue title="Proveedor" id="scrollbar-modal" maxWidth="3xl" :modalOpen="scrollbarModalOpen"
            @close-modal-persona="this.$emit('close-definitive')">
            <div class="px-5 py-4">
                <div class="space-y-2">
                    <div id="personal-information">
                        <div class="pb-4 mb-4 md:flex flex-row justify-between">
                            <span class="font-semibold text-slate-800 mb-2 text-lg underline underline-offset-2">
                                Información general
                            </span>
                            <span class="">
                                <div class=" overflow-hidden rounded border border-slate-200  ">
                                    <table id="resumen" class="table table-bordered text-center p-0 mt-2 w-full">
                                        <tbody>
                                            <tr>
                                                <td class="border px-3 text-xs">IVA SUJETO DE RETENCION<br />
                                                    <span class="text-red-500 text-xs">{{ (supplier.sujetoRetencion.iva * 100)}}%</span>
                                                </td>
                                                <td class="border px-3 text-xs">ISRL SUJETO DE RETENCION<br />
                                                    <span class="text-red-500 text-xs">{{ (supplier.sujetoRetencion.isrl * 100)}}%</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="border text-xs" colspan="2">FECHA DE CREACION: 
                                                    <span class="text-xs underline">{{  moment( supplier.fecha_registro_proveedor).format('MMMM Do YYYY') }}</span>
                                                   <!--  <span class="text-xs">{{ supplier.fecha_registro_proveedor }}</span>
                                                    {{ moment( supplier.fecha_registro_proveedor).format('dddd Do MMMM YYYY') }} -->
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </span>
                        </div>
                        <div class="mb-7 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="razon-social" v-model="supplier.razon_social_proveedor"
                                    :value="supplier.razon_social_proveedor" type="text" placeholder="Razón social">
                                    <LabelToInput icon="standard" forLabel="razon-social" />
                                </TextInput>
                                <InputError class="mt-2" :message="errosModel.razon_social_proveedor" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="nombre-comercial" v-model="supplier.nombre_comercial_proveedor"
                                    :value="supplier.nombre_comercial_proveedor" type="text" placeholder="Nombre comercial">
                                    <LabelToInput icon="standard" forLabel="nombre-comercial" />
                                </TextInput>
                                <InputError class="mt-2" :message="errosModel.nombre_comercial_proveedor" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="nrc-proveedor" v-model="supplier.nrc_proveedor"
                                    @update:modelValue="typeNrcSupplier()" :value="supplier.nrc_proveedor" type="text"
                                    placeholder="NRC Proveedor">
                                    <LabelToInput icon="personalInformation" forLabel="nrc-proveedor" />
                                </TextInput>
                                <InputError class="mt-2" :message="errosModel.nrc_proveedor" />
                            </div>
                        </div>
                        <div class="mb-7 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="dui-proveedor" v-model="supplier.dui_proveedor"
                                    @update:modelValue="typeDuiSupplier()" :value="supplier.dui_proveedor" type="text"
                                    placeholder="Dui">
                                    <LabelToInput icon="personalInformation" forLabel="dui-proveedor" />
                                </TextInput>
                                <InputError class="mt-2" :message="errosModel.dui_proveedor" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="nit-proveedor" v-model="supplier.nit_proveedor"
                                    @update:modelValue="typeNitSupplier()" :value="supplier.nit_proveedor" type="text"
                                    placeholder="NIT">
                                    <LabelToInput icon="personalInformation" forLabel="nit-proveedor" />
                                </TextInput>
                                <InputError class="mt-2" :message="errosModel.nit_proveedor" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Tipo de contribuyente <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold  flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Tipo contribuyente" v-model="supplier.id_tipo_contribuyente"
                                        id="estado-civil" :options="allSelectOptios.typeContribuyent" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="errosModel.id_tipo_contribuyente" />
                            </div>
                        </div>
                        <div class="mb-7 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Sujeto de retencion <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold  flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Sujeto retencion" v-model="supplier.id_sujeto_retencion"
                                        id="estado-civil" @select="evaluteValue($event)"
                                        :options="allSelectOptios.retention" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="errosModel.id_sujeto_retencion" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Giro <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold  flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Giro" v-model="supplier.id_giro" id="giro"
                                        :options="allSelectOptios.giro" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="errosModel.id_giro" />
                            </div>
                        </div>
                    </div>

                    <div id="addres-information">
                        <div class="pb-4 mb-4 md:flex flex-row justify-between">
                            <span class="font-semibold text-slate-800 mb-2 text-lg underline underline-offset-2">
                                Dirección e información adicional
                            </span>
                        </div>
                        <div class="mb-7 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="telefono" v-model="supplier.telefono1_proveedor"
                                    :value="supplier.telefono1_proveedor" type="text" placeholder="Telefono"
                                    @update:modelValue="telefonoProveedor()">
                                    <LabelToInput icon="personalPhoneNumber" forLabel="telefono" />
                                </TextInput>
                                <InputError class="mt-2" :message="errosModel.telefono1_proveedor" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="telefono2-proveedor" v-model="supplier.telefono2_proveedor"
                                    @update:modelValue="telefono2Proveedor()" :value="supplier.telefono2_proveedor"
                                    type="text" placeholder="Telefono  (opcionar)">
                                    <LabelToInput icon="personalPhoneNumber" forLabel="telefono2-proveedor" />
                                </TextInput>
                                <InputError class="mt-2" :message="errosModel.telefono2_proveedor" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="Direccion" v-model="supplier.direccion_proveedor"
                                    :value="supplier.direccion_proveedor" type="tex" placeholder="Direccion">
                                    <LabelToInput icon="house" forLabel="Direccion" />
                                </TextInput>
                                <InputError class="mt-2" :message="errosModel.direccion_proveedor" />
                            </div>
                        </div>
                        <div class="mb-7 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <TextInput id="contacto-proveedor" v-model="supplier.contacto_proveedor"
                                    :value="supplier.contacto_proveedor" type="text" placeholder="Contacto ">
                                    <LabelToInput icon="personalInformation" forLabel="contacto-proveedor" />
                                </TextInput>
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Municipio <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold  flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Municipio" v-model="supplier.id_municipio" id="estado-civil"
                                        :options="allSelectOptios.location" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="errosModel.id_municipio" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <TextInput id="email-proveedor" v-model="supplier.email_proveedor"
                                    :value="supplier.email_proveedor" type="email" placeholder="Email ">
                                    <LabelToInput icon="email" forLabel="email-proveedor" />
                                </TextInput>
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
                    <GeneralButton v-if="infoSupplier != ''" @click="updateSupplier()"
                        color="bg-orange-700  hover:bg-orange-800" text="Editar proveedor" icon="add" />
                    <GeneralButton v-else @click="addSupplier()" color="bg-green-700  hover:bg-green-800"
                        text="Agregar proveedor" icon="add" />
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
        infoSupplier: {
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
                location: [],
                typeContribuyent: [],
                retention: [],
                giro: [],
            },
            supplier: {
                razon_social_proveedor: '',
                nombre_comercial_proveedor: '',
                nrc_proveedor: '',
                dui_proveedor: '',
                nit_proveedor: '',
                id_tipo_contribuyente: '',
                id_sujeto_retencion: '',
                telefono1_proveedor: '',
                telefono2_proveedor: '',
                contacto_proveedor: '',
                id_municipio: '',
                direccion_proveedor: '',
                id_proveedor: '',
                id_giro: '',
                email_proveedor: '',
                sujetoRetencion: {
                    iva: '',
                    isrl: '',
                },
                fecha_registro_proveedor: '',
            },
            errosModel: {}
        }
    },
    methods: {
        async listOptionsSelect() {//metodo que trae toda la info de todos los select
            await axios.get("/list-option-select-suppliers").then((response) => {
                this.allSelectOptios.location = response.data.location
                this.allSelectOptios.typeContribuyent = response.data.typeContribuyent
                this.allSelectOptios.retention = response.data.retention
                this.allSelectOptios.giro = response.data.giro

            });
        },
        telefonoProveedor() {
            var telefono = this.supplier.telefono1_proveedor.replace(/\D/g, '').match(/(\d{0,4})(\d{0,4})/);
            this.supplier.telefono1_proveedor = !telefono[2] ? telefono[1] : '' + telefono[1] + '-' + telefono[2];
        },
        telefono2Proveedor() {
            var telefono2 = this.supplier.telefono2_proveedor.replace(/\D/g, '').match(/(\d{0,4})(\d{0,4})/);
            this.supplier.telefono2_proveedor = !telefono2[2] ? telefono2[1] : '' + telefono2[1] + '-' + telefono2[2];
        },
        typeDuiSupplier() {
            var dui = this.supplier.dui_proveedor.replace(/\D/g, '').match(/(\d{0,8})(\d{0,1})/);
            this.supplier.dui_proveedor = !dui[2] ? dui[1] : '' + dui[1] + '-' + dui[2];
        },
        typeNitSupplier() {
            const regex = /^[0-9\-]+$/;
            if (!regex.test(this.supplier.nit_proveedor)) {
                this.supplier.nit_proveedor = this.supplier.nit_proveedor.replace(/[^0-9\-]/g, '');
            }
        },
        typeNrcSupplier() {
            const regex = /^[0-9\-]+$/;
            if (!regex.test(this.supplier.nrc_proveedor)) {
                this.supplier.nrc_proveedor = this.supplier.nrc_proveedor.replace(/[^0-9\-]/g, '');
            }
        },
        updateSupplier() {

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
                    axios.post('/update-supplier', this.supplier).then((response) => {
                        toast.success("El registro se edito con exito", {
                            autoClose: 5000,
                            position: "top-right",
                            transition: "zoom",
                            toastBackgroundColor: "white",
                        });
                    }).catch((Error) => {
                        if (Error.response.status === 422) {
                            let data = Error.response.data.errors
                            var myData = new Object();
                            for (const errorBack in data) {
                                myData[errorBack] = data[errorBack][0]
                            }
                            this.errosModel = myData;
                            toast.warning("Tienes algunos errores por favor verifica tus datos", {
                                autoClose: 5000,
                                position: "top-right",
                                transition: "zoom",
                                toastBackgroundColor: "white",
                            });
                            setTimeout(() => {
                                this.errosModel = {}
                            }, 9000);
                        }
                    });
                }
            })


        },
        addSupplier() {
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
                    axios.post('/add-supplier', this.supplier).then((response) => {
                        toast.success("El registro se ha agregado correctamente", {
                            autoClose: 5000,
                            position: "top-right",
                            transition: "zoom",
                            toastBackgroundColor: "white",
                        });
                        this.limpiarCampos()
                        this.$emit("showTableAgain")
                    }).catch((Error) => {
                        if (Error.response.status === 422) {
                            let data = Error.response.data.errors
                            var myData = new Object();
                            for (const errorBack in data) {
                                myData[errorBack] = data[errorBack][0]
                            }
                            this.errosModel = myData;
                            toast.warning("Tienes algunos errores por favor verifica tus datos", {
                                autoClose: 9000,
                                position: "top-right",
                                transition: "zoom",
                                toastBackgroundColor: "white",
                            });
                            setTimeout(() => {
                                this.errosModel = {}
                            }, 9000);
                        }
                    });
                }
            })
        },
        evaluteValue(id_sujeto_retencion) {
            axios.post('/change-values-retencion/' + id_sujeto_retencion).then((response) => {
                this.supplier.sujetoRetencion.iva = response.data[0].iva_sujeto_retencion
                this.supplier.sujetoRetencion.isrl = response.data[0].isrl_sujeto_retencion
            })

        },
        limpiarCampos() {
            this.supplier.id_proveedor = ''
            this.supplier.razon_social_proveedor = ''
            this.supplier.nombre_comercial_proveedor = ''
            this.supplier.nrc_proveedor = ''
            this.supplier.dui_proveedor = ''
            this.supplier.nit_proveedor = ''
            this.supplier.id_tipo_contribuyente = ''
            this.supplier.id_sujeto_retencion = ''
            this.supplier.telefono1_proveedor = ''
            this.supplier.telefono2_proveedor = ''
            this.supplier.contacto_proveedor = ''
            this.supplier.id_municipio = ''
            this.supplier.direccion_proveedor = ''
            this.supplier.fecha_registro_proveedor = ''
            this.supplier.id_giro = ''
            this.supplier.email_proveedor = ''

            this.supplier.sujetoRetencion.isrl = '00.00'
            this.supplier.sujetoRetencion.iva = '00.00'

        }

    },
    created() {
        this.listOptionsSelect()
    },
    watch: {
        infoSupplier: function (value, oldvalue) {
            this.supplier.id_proveedor = this.infoSupplier.id_proveedor
            this.supplier.razon_social_proveedor = this.infoSupplier.razon_social_proveedor
            this.supplier.nombre_comercial_proveedor = this.infoSupplier.nombre_comercial_proveedor
            this.supplier.nrc_proveedor = this.infoSupplier.nrc_proveedor
            this.supplier.dui_proveedor = this.infoSupplier.dui_proveedor
            this.supplier.nit_proveedor = this.infoSupplier.nit_proveedor
            this.supplier.id_tipo_contribuyente = this.infoSupplier.id_tipo_contribuyente
            this.supplier.id_sujeto_retencion = this.infoSupplier.id_sujeto_retencion
            this.supplier.telefono1_proveedor = this.infoSupplier.telefono1_proveedor
            this.supplier.telefono2_proveedor = this.infoSupplier.telefono2_proveedor
            this.supplier.contacto_proveedor = this.infoSupplier.contacto_proveedor
            this.supplier.id_municipio = this.infoSupplier.id_municipio
            this.supplier.direccion_proveedor = this.infoSupplier.direccion_proveedor
            this.supplier.fecha_registro_proveedor = this.infoSupplier.fecha_reg_proveedor
            this.supplier.id_giro = this.infoSupplier.id_giro
            this.supplier.email_proveedor = this.infoSupplier.email_proveedor

            if (this.infoSupplier != "") {
                this.supplier.sujetoRetencion.isrl = this.infoSupplier.sujeto_retencion.isrl_sujeto_retencion
                this.supplier.sujetoRetencion.iva = this.infoSupplier.sujeto_retencion.iva_sujeto_retencion
            } else {
                this.supplier.sujetoRetencion.isrl = '00.00'
                this.supplier.sujetoRetencion.iva = '00.00'
            }



        }
    }
}
</script>

<style>
#resumen {
    padding: 0;
    margin: 0;
    border-collapse: collapse;
}
#resumen td {
    /* Agregando tamaño a letra a tabla resumen */
    font-size: 10px;
    font-weight: 900;
}
</style>