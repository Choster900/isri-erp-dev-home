<script setup>
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import { localeData } from 'moment_spanish_locale';
import moment from 'moment';
import Modal from "@/Components-ISRI/AllModal/Modal.vue";
moment.locale('es', localeData)
</script>
<template>
    <div class="m-1.5">
        <Modal :show="ModalIsOpen" @close="$emit('close-modal')" modal-title="Proveedores" maxWidth="3xl">
            <div class="px-5 py-4">
                <div class="space-y-1">
                    <div id="personal-information">
                        <div class="pb-4 md:flex flex-row justify-between">
                            <div class="font-semibold  text-slate-800 text-lg underline underline-offset-2">
                                Información general
                            </div>
                            <div class="overflow-hidden rounded border border-slate-200  ">
                                <table id="resumen" class="table table-bordered text-center p-0 mt-2 w-full">
                                    <tbody>
                                        <tr>
                                            <td class="border px-3 text-xs">IVA SUJETO DE RETENCION<br />
                                                <span class="text-red-500 text-xs font-medium">{{
                                                    (supplier.sujetoRetencion.iva * 100) }}%</span>
                                            </td>
                                            <td class="border px-3 text-xs">ISRL SUJETO DE RETENCION<br />
                                                <span class="text-red-500 text-xs font-medium">{{
                                                    (supplier.sujetoRetencion.isrl * 100) }}%</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="border text-xs" colspan="2">FECHA DE CREACION:
                                                <span class="text-xs font-medium">{{
                                                    moment(supplier.fecha_registro_proveedor).format('DD MMMM YYYY')
                                                }}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="razon-social" v-model="supplier.razon_social_proveedor" type="text"
                                    placeholder="Razón social"
                                    @update:modelValue="validateInput('razon_social_proveedor', limit = 145, upper = true)">
                                    <LabelToInput icon="standard" forLabel="razon-social" />
                                </TextInput>
                                <InputError class="mt-2" :message="errosModel.razon_social_proveedor" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="nombre-comercial" v-model="supplier.nombre_comercial_proveedor" type="text"
                                    placeholder="Nombre comercial"
                                    @update:modelValue="validateInput('nombre_comercial_proveedor', limit = 145, upper = true)">
                                    <LabelToInput icon="standard" forLabel="nombre-comercial" />
                                </TextInput>
                                <InputError class="mt-2" :message="errosModel.nombre_comercial_proveedor" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput :required="false" id="nrc-proveedor" v-model="supplier.nrc_proveedor" type="text"
                                    @update:modelValue="validarnrc()" placeholder="NRC Proveedor">
                                    <LabelToInput icon="personalInformation" forLabel="nrc-proveedor" />
                                </TextInput>
                                <InputError class="mt-2" :message="errosModel.nrc_proveedor" />
                            </div>
                        </div>
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="dui-proveedor" v-model="supplier.dui_proveedor" type="text" placeholder="Dui"
                                    @update:modelValue="validateInput('dui_proveedor', limit = 10, false, false, dui = true)">
                                    <LabelToInput icon="personalInformation" forLabel="dui-proveedor" />
                                </TextInput>
                                <InputError class="mt-2" :message="errosModel.dui_proveedor" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput :required="false" id="nit-proveedor" v-model="supplier.nit_proveedor" type="text"
                                    placeholder="NIT"
                                    @update:modelValue="validateInput('nit_proveedor', limit = 17, false, false, false, nit = true)">
                                    <LabelToInput icon="personalInformation" forLabel="nit-proveedor" />
                                </TextInput>
                                <InputError class="mt-2" :message="errosModel.nit_proveedor" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Tipo de contribuyente <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-medium  flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Tipo contribuyente" v-model="supplier.id_tipo_contribuyente"
                                        id="estado-civil" :options="allSelectOptios.typeContribuyent" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="errosModel.id_tipo_contribuyente" />
                            </div>
                        </div>
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Sujeto de retencion <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-medium  flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Sujeto retencion" v-model="supplier.id_sujeto_retencion"
                                        id="estado-civil" @select="evaluteValue($event)"
                                        :options="allSelectOptios.retention" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="errosModel.id_sujeto_retencion" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Giro
                                </label>
                                <div class="relative font-medium  flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Giro" v-model="supplier.id_giro" id="giro"
                                        :options="allSelectOptios.giro" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="errosModel.id_giro" />
                            </div>
                        </div>
                    </div>

                    <div id="addres-information">
                        <div class="mb-3 md:flex flex-row justify-between">
                            <span class="font-semibold text-slate-800 mb-2 text-lg underline underline-offset-2">
                                Dirección e información adicional
                            </span>
                        </div>
                        <div class="mb-4 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput :required="false" id="telefono" v-model="supplier.telefono1_proveedor"
                                    type="text" placeholder="Telefono 1"
                                    @update:modelValue="validateInput('telefono1_proveedor', limit = 10, false, false, false, false, phone_numer = true)">
                                    <LabelToInput icon="personalPhoneNumber" forLabel="telefono" />
                                </TextInput>
                                <InputError class="mt-2" :message="errosModel.telefono1_proveedor" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput :required="false" id="telefono2-proveedor" v-model="supplier.telefono2_proveedor"
                                    type="text" placeholder="Telefono 2"
                                    @update:modelValue="validateInput('telefono2_proveedor', limit = 10, false, false, false, false, phone_numer = true)">
                                    <LabelToInput icon="personalPhoneNumber" forLabel="telefono2-proveedor" />
                                </TextInput>
                                <InputError class="mt-2" :message="errosModel.telefono2_proveedor" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput :required="false" id="Direccion" v-model="supplier.direccion_proveedor"
                                    type="tex" placeholder="Direccion"
                                    @update:modelValue="validateInput('direccion_proveedor', limit = 250, false, false, false, false, false)">
                                    <LabelToInput icon="house" forLabel="Direccion" />
                                </TextInput>
                                <InputError class="mt-2" :message="errosModel.direccion_proveedor" />
                            </div>
                        </div>
                        <div class="mb-1 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <TextInput :required="false" id="contacto-proveedor" v-model="supplier.contacto_proveedor"
                                    type="text" placeholder="Contacto "
                                    @update:modelValue="validateInput('contacto_proveedor', limit = 95, false, false, false, false, false)">
                                    <LabelToInput icon="personalInformation" forLabel="contacto-proveedor" />
                                </TextInput>
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Municipio <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-medium  flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Municipio" v-model="supplier.id_municipio" id="estado-civil"
                                        :options="allSelectOptios.location" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="errosModel.id_municipio" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <TextInput :required="false" id="email-proveedor" v-model="supplier.email_proveedor"
                                    type="email" placeholder="Email "
                                    @update:modelValue="validateInput('email_proveedor', limit = 95, false, false, false, false, false)">
                                    <LabelToInput icon="email" forLabel="email-proveedor" />
                                </TextInput>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sticky bottom-0 px-5 py-2 bg-white border-t border-slate-200">
                <div class="flex flex-wrap justify-end space-x-3">
                    <button
                        class="btn-sm border-slate-200 hover:border-slate-300 text-slate-600 underline underline-offset-1"
                        @click.stop="$emit('close-modal')">Cerrar</button>
                    <GeneralButton v-if="infoSupplier != ''" @click="updateSupplier()"
                        color="bg-orange-700  hover:bg-orange-800" text="Editar proveedor" icon="update" />
                    <GeneralButton v-else @click="addSupplier()" color="bg-green-700  hover:bg-green-800"
                        text="Agregar proveedor" icon="add" />
                </div>
            </div>

        </Modal>
        <!-- End -->
    </div>
</template>
<script>
export default {
    props: {
        ModalIsOpen: {
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
        //Function to validate data entry
        validateInput(field, limit, upper_case, number, dui, nit, phone_number, nrc) {
            if (this.supplier[field] && this.supplier[field].length > limit) {
                this.supplier[field] = this.supplier[field].substring(0, limit);
            }
            if (upper_case) {
                this.toUpperCase(field)
            }
            if (number) {
                this.onlyNumbersAndSixDigits(field)
            }
            if (dui) {
                //Revisar funcion, al borrar un numero regresa el apuntador al final
                this.typeDuiSupplier(field)
            }
            if (nrc) {
                //Revisar funcion, al borrar un numero regresa el apuntador al final
                this.typeNrc(field)
            }
            if (nit) {
                //Verificar ya que al borrar numeros los guiones siempre quedan visibles
                this.typeNitSupplier(field)
            }
            if (phone_number) {
                this.phoneNumberFormat(field)
            }
            if (nrc) {
                this.phoneNumberFormat(field)
            }
        },
        validarnrc() {
            // Remover todos los caracteres excepto los números y guiones
            this.supplier.nrc_proveedor = this.supplier.nrc_proveedor.replace(/[^0-9-]/g, "");

            // Limitar la entrada a un máximo de 7 caracteres
            if (this.supplier.nrc_proveedor.length > 8) {
                this.supplier.nrc_proveedor = this.supplier.nrc_proveedor.slice(0, 8);
            }
        },
        async listOptionsSelect() {//metodo que trae toda la info de todos los select
            await axios.get("/list-option-select-suppliers").then((response) => {
                this.allSelectOptios.location = response.data.location
                this.allSelectOptios.typeContribuyent = response.data.typeContribuyent
                this.allSelectOptios.retention = response.data.retention
                this.allSelectOptios.giro = response.data.giro
            })
                .catch(errors => {
                    let msg = this.manageError(errors);
                    this.$swal.fire({
                        title: "Operación cancelada",
                        text: msg,
                        icon: "warning",
                        timer: 5000,
                    });
                });
        },
        toUpperCase(field) {
            //Converts field to uppercase
            this.supplier[field] = this.supplier[field].toUpperCase()
        },
        phoneNumberFormat(field) {
            //Validates the phone number format
            var telefono = this.supplier[field].replace(/\D/g, '').match(/(\d{0,4})(\d{0,4})/);
            this.supplier[field] = !telefono[2] ? telefono[1] : '' + telefono[1] + '-' + telefono[2];
        },
        typeDuiSupplier(field) {
            //Specific format for dui number
            var dui = this.supplier[field].replace(/\D/g, '').match(/(\d{0,8})(\d{0,1})/);
            this.supplier[field] = !dui[2] ? dui[1] : '' + dui[1] + '-' + dui[2];
        },
        typeNitSupplier(field) {
            let nit = this.supplier[field].replace(/\D/g, '').match(/(\d{0,4})(\d{0,6})(\d{0,3})(\d{0,1})/);

            let formattedNit = nit[1];

            if (nit[2]) {
                formattedNit += "-" + nit[2];
            }

            if (nit[3]) {
                formattedNit += "-" + nit[3];
            }

            if (nit[4]) {
                formattedNit += "-" + nit[4];
            }

            this.supplier[field] = formattedNit;
        },
        typeNrc(field) {
            //Specific format for nit number
            // Remover todos los caracteres excepto los números y guiones
            this.supplier.nrc_proveedor = this.supplier.nrc_proveedor.replace(/[^0-9-]/g, "");

            // Colocar guiones después de los primeros cuatro dígitos
            if (this.supplier.nrc_proveedor.length > 4) {
                this.supplier.nrc_proveedor = this.supplier.nrc_proveedor.slice(0, 4) + "-" + this.supplier.nrc_proveedor.slice(4);
            }
        },
        onlyNumbersAndSixDigits(field) {
            //Allows only numbers and six digits
            const regex = /^\d{0,6}$/;
            if (!regex.test(this.supplier[field])) {
                this.supplier[field] = this.supplier[field].replace(/[^\d]/g, '').substring(0, 6);
            }
        },
        updateSupplierRequest() {


            return new Promise(async (resolve, reject) => {
                try {
                    const resp = await axios.post('/update-supplier', this.supplier)
                    this.$emit("reload-table")
                        this.limpiarCampos()
                    resolve(resp); // Resolvemos la promesa con la respuesta exitosa
                } catch (errors) {
                    if (errors.response.status === 422) {
                            if (errors.response.data.logical_error) {
                                toast.error(
                                    errors.response.data.logical_error,
                                    {
                                        autoClose: 5000,
                                        position: "top-right",
                                        transition: "zoom",
                                        toastBackgroundColor: "white",
                                    }
                                );
                            } else {
                                let data = errors.response.data.errors
                                var myData = new Object();
                                for (const errorBack in data) {
                                    myData[errorBack] = data[errorBack][0]
                                }
                                this.errosModel = myData;
                            }
                        } else {
                            let msg = this.manageError(errors);
                            this.$swal.fire({
                                title: "Operación cancelada",
                                text: msg,
                                icon: "warning",
                                timer: 5000,
                            });
                        }
                    reject(Error); // Rechazamos la promesa en caso de excepción

                }
            });
        },

        async updateSupplier() {
            // Mostrar confirmación al usuario
            const confirmed = await this.$swal.fire({
                title: '<p class="text-[20pt] text-center">¿Esta seguro de agregar el proveedor?</p>',
                icon: 'question',
                iconHtml: `<lord-icon src="https://cdn.lordicon.com/enzmygww.json" trigger="loop" delay="500" colors="primary:#121331" style="width:100px;height:100px"></lord-icon>`,
                confirmButtonText: 'Si, Editar',
                confirmButtonColor: '#001b47',
                cancelButtonText: 'Cancelar',
                showCancelButton: true,
                showCloseButton: true,
            });

            if (confirmed.isConfirmed) {
                // Verificar si no hay posiciones duplicadas de números de acta
                this.executeRequest(
                    this.updateSupplierRequest(),
                    '¡El proveedor se han actualizado correctamente!'
                )

            }
        },

        addSupplierRequest() {
            return new Promise(async (resolve, reject) => {
                try {
                    const resp = await axios.post('/add-supplier', this.supplier)
                    this.$emit("reload-table")
                    this.limpiarCampos()
                    resolve(resp); // Resolvemos la promesa con la respuesta exitosa
                } catch (Error) {
                    console.log(Error);
                    if (Error.response.status === 422) {
                        let data = Error.response.data.errors
                        var myData = new Object();
                        for (const errorBack in data) {
                            myData[errorBack] = data[errorBack][0]
                        }
                        this.errosModel = myData;
                    } else {
                        let msg = this.manageError(Error);
                        this.$swal.fire({
                            title: "Operación cancelada",
                            text: msg,
                            icon: "warning",
                            timer: 5000,
                        });
                    }
                    reject(Error); // Rechazamos la promesa en caso de excepción

                }
            });
        },

        async addSupplier() {
            // Mostrar confirmación al usuario
            const confirmed = await this.$swal.fire({
                title: '<p class="text-[20pt] text-center">¿Esta seguro de agregar el proveedor?</p>',
                icon: 'question',
                iconHtml: `<lord-icon src="https://cdn.lordicon.com/enzmygww.json" trigger="loop" delay="500" colors="primary:#121331" style="width:100px;height:100px"></lord-icon>`,
                confirmButtonText: 'Si, Editar',
                confirmButtonColor: '#001b47',
                cancelButtonText: 'Cancelar',
                showCancelButton: true,
                showCloseButton: true,
            });

            if (confirmed.isConfirmed) {
                // Verificar si no hay posiciones duplicadas de números de acta
                this.executeRequest(
                    this.addSupplierRequest(),
                    'El proveedor se han guardado correctamente!'
                )

            }
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
        ModalIsOpen: function (value) {
            if (value) {
                this.errosModel = {}
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
        },
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
