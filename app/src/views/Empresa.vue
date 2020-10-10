<template>
  <v-content>
    <v-row align="center" class="pa-5 align-center">
      <v-col cols="4">
        <v-alert
          v-model="alert"
          text
          prominent
          type="error"
          icon="mdi-cloud-alert"
          close-text="Close Alert"
          dismissible
          >Ocurrio un error.</v-alert
        >
      </v-col>
    </v-row>
    <v-row class="pa-5 align-center">
      <v-col>
        <v-btn
          fab
          large
          dark
          color="blue darken-3"
          @click="dialogEjemplo = true"
        >
          <v-icon>mdi-plus</v-icon>
        </v-btn>
      </v-col>
      <v-col cols="11">
        <h2 class="font-weight-regular text-center">
          Mantenimiento de Empresa
        </h2>
      </v-col>
    </v-row>
    <v-dialog v-model="dialogEjemplo" persistent scrollable max-width="60vw">
      <v-card>
        <v-card-title class="headline indigo darken-4">
          <span v-if="edit" class="headline" style="color: white"
            >Editar Empresa</span
          >
          <span v-else-if="ver" class="headline" style="color: white"
            >Ver Empresa</span
          >
          <span v-else class="headline" style="color: white"
            >Nueva Empresa</span
          >
        </v-card-title>
        <v-card-text>
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-row>
              <v-col cols="6">
                <v-text-field
                  :disabled="ver"
                  v-model="RazonSocial"
                  :rules="[fieldRules.required]"
                  label="Razón Social"
                  prepend-icon="mdi-domain"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  :disabled="ver"
                  v-model="RUC"
                  :rules="[fieldRules.required, fieldRules.validateRuc]"
                  label="RUC"
                  type="number"
                  maxlength="11"
                  prepend-icon="mdi-domain"
                  required
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="6">
                <v-text-field
                  :disabled="ver"
                  v-model="Facebook"
                  label="Facebook"
                  prepend-icon="mdi-facebook-box"
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  :disabled="ver"
                  v-model="Instagram"
                  label="Instagram"
                  prepend-icon="mdi-instagram"
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="6">
                <v-text-field
                  :disabled="ver"
                  v-model="Youtube"
                  label="Youtube"
                  prepend-icon="mdi-youtube"
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  :disabled="ver"
                  v-model="Whatsapp"
                  label="Whatsapp"
                  prepend-icon="mdi-whatsapp"
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="6">
                <v-text-field
                  :disabled="ver"
                  v-model="Correo"
                  :rules="[fieldRules.required, fieldRules.email]"
                  label="Correo"
                  prepend-icon="mdi-email"
                  required
                ></v-text-field>
              </v-col>
              <v-col v-if="!ver" cols="6">
                <v-file-input
                  v-model="Logo"
                  label="Logo"
                  prepend-icon="mdi-camera"
                  accept="image/*"
                ></v-file-input>
              </v-col>
              <v-col v-else cols="12">
                <v-img :src="Logo"></v-img>
              </v-col>
            </v-row>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            v-if="!ver"
            color="indigo darken-4"
            text
            @click="(dialogEjemplo = false), limpiar()"
            >Cerrar</v-btn
          >
          <v-btn
            v-if="!ver"
            :loading="saveLoading"
            :disabled="saveLoading"
            color="indigo darken-4"
            class="ma-2 white--text"
            depressed
            @mousedown="validate"
            @click="executeEventClick"
            >Guardar</v-btn
          >
          <v-btn
            v-if="ver"
            color="indigo darken-4"
            text
            @click="(dialogEjemplo = false), limpiar()"
            >Cancelar</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-data-table :headers="headers2"  :items="empresas" :search="search">
      <template v-slot:[`item.Vigencia`]="{ item }">
        <v-chip :color="item.Vigencia ? 'green' : 'red'" dark></v-chip>
      </template>
      <template v-slot:[`item.actions`]="{ item }">
        <v-icon class="mr-2" @click="verEmpresa(item)">mdi-eye</v-icon>
        <v-icon class="mr-2" @click="cambiarEstadoEmpresa(item)">{{
          item.Vigencia ? "mdi-do-not-disturb" : "mdi-check-box-outline"
        }}</v-icon>
      </template>
    </v-data-table>
    <!-- <tables-mostrar
      :headers="headers"
      :options="options"
      :withOptions="true"
      ref="empresaTable"
      entity="empresas"
    /> -->
    <!-- <v-tabs>
      <v-tab>Tabla 1</v-tab>
      <v-tab>Tabla 2</v-tab>
      <v-tab-item>
        <tables-mostrar
          :headers="headers"
          :options="options"
          :withOptions="true"
          ref="empresaTable"
          entity="empresas"
        />
      </v-tab-item>
      <v-tab-item>
        <v-row class="justify-center">
          <v-col cols="11">
            <v-card>
              <v-card-title>
                <v-spacer></v-spacer>
                <v-text-field
                  v-model="search"
                  append-icon="mdi-magnify"
                  label="Buscar"
                  single-line
                  hide-details
                ></v-text-field>
              </v-card-title>
              <v-data-table :headers="headers2" :items="empresas" :search="search">
                <template v-slot:[`item.Vigencia`]="{ item }">
                  <v-chip :color="item.Vigencia? 'green': 'red'" dark></v-chip>
                </template>
                <template v-slot:[`item.actions`]="{ item }">
                  <v-icon class="mr-2" @click="verEmpresa(item)">mdi-eye</v-icon>
                  <v-icon
                    class="mr-2"
                    @click="cambiarEstadoEmpresa(item)"
                  >{{ item.Vigencia? 'mdi-do-not-disturb': 'mdi-check-box-outline' }}</v-icon>
                </template>
              </v-data-table>
            </v-card>
          </v-col>
        </v-row>
      </v-tab-item>
    </v-tabs> -->
  </v-content>
</template>

<script>
import { del, get, enviarConArchivos, patch } from "../api/api";
import { logos } from "../api/config";

export default {
  // components: {
  //   TablesMostrar: () => import("../components/TablesMostrar"),
  // },
  data() {
    return {
      edit: false,
      ver: false,
      alert: false,
      valid: true,
      saveLoading: false,
      dialogEjemplo: false,
      fieldRules: {
        required: (v) => !!v || "Campo requerido",
        email: (v) => {
          const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          return pattern.test(v) || "Correo electrónico incorrecto.";
        },
        validateRuc: (v) => v.length == 11 || "RUC incorrecto.",
      },
      headers: [
        {
          text: "RUC",
          align: "start",
          sortable: false,
          value: "RUC",
          width: "20%",
        },
        { text: "Razon Social", value: "RazonSocial", width: "30%" },
        { text: "Correo", value: "Correo", width: "40%" },
        { text: "Acciones", value: "actions", width: "10%" },
      ],
      headers2: [
        {
          text: "RUC",
          align: "start",
          sortable: false,
          value: "RUC",
        },
        { text: "Razon Social", value: "RazonSocial" },
        { text: "Correo", value: "Correo" },
        { text: "Vigencia", value: "Vigencia" },
        { text: "Acciones", value: "actions" },
      ],
      options2: [
        {
          name: "Ver",
          icon: "mdi-eye",
          function: this.showEditEmpresa,
        },
        {
          name: "Cambiar vigencia",
          icon: "mdi-check-box-outline",
          function: this.deleteEmpresa,
        },
      ],
      options: [
        {
          name: "Editar",
          icon: "mdi-pencil",
          function: this.showEditEmpresa,
        },
        {
          name: "Cambiar vigencia",
          icon: "mdi-check-box-outline",
          function: this.deleteEmpresa,
        },
      ],
      empresas: [],
      search: "",
      RUC: "",
      RazonSocial: "",
      Facebook: "",
      Instagram: "",
      Youtube: "",
      Whatsapp: "",
      Correo: "",
      Logo: null,
    };
  },
  methods: {
    validate() {
      this.$refs.form.validate();
    },
    limpiar() {
      this.RazonSocial = "";
      this.RUC = "";
      this.Instagram = "";
      this.Facebook = "";
      this.Youtube = "";
      this.Whatsapp = "";
      this.Correo = "";
      this.Logo = null;
      this.ver = false;
      this.edit = false;
    },
    executeEventClick() {
      if (this.edit === false) {
        this.registerEmpresa();
      } else {
        this.editEmpresa();
      }
    },
    assembleEmpresa() {
      let form = new FormData();
      form.append("RazonSocial", this.RazonSocial);
      form.append("RUC", this.RUC);
      form.append("Facebook", this.Facebook);
      form.append("Instagram", this.Instagram);
      form.append("Youtube", this.Youtube);
      form.append("Whatsapp", this.Whatsapp);
      form.append("Correo", this.Correo);
      form.append("Logo", this.Logo);
      return form;
    },

    registerEmpresa() {
      if (this.valid == false) return;
      this.saveLoading = true;
      enviarConArchivos("empresas", this.assembleEmpresa()).then(() => {
        this.saveLoading = false;
        this.dialogEjemplo = false;
        // this.$refs.empresaTable.fetchData();
        this.limpiar();
        this.actualizarEmpresas();
      });
    },
    editEmpresa() {
      if (this.valid == false) return;
      this.saveLoading = true;
      enviarConArchivos("empresas/" + this.editId, this.assembleEmpresa())
        .then(() => {
          this.saveLoading = false;
          this.editId = null;
          this.dialogEjemplo = false;
          // this.$refs.empresaTable.fetchData();
          this.limpiar();
        })
        .catch(() => {
          this.alert = true;
        });
    },
    showEditEmpresa(empresa) {
      this.edit = true;
      this.editId = empresa.Codigo;
      this.mostrarEmpresa(empresa.Codigo).then(() => {
        this.dialogEjemplo = true;
      });
    },
    verEmpresa(empresa) {
      this.ver = true;
      this.mostrarEmpresa(empresa.Codigo).then(() => {
        this.dialogEjemplo = true;
      });
    },
    deleteEmpresa(empresa) {
      del("empresa/" + empresa.Codigo)
        .then(() => {
          this.$refs.empresaTable.fetchData();
          this.actualizarEmpresas();
        })
        .catch(() => {
          this.alert = true;
        });
    },
    cambiarEstadoEmpresa(empresa) {
      patch("empresa/" + empresa.Codigo)
        .then(() => {
          this.$refs.empresaTable.fetchData();
          this.actualizarEmpresas();
        })
        .catch(() => {
          this.alert = true;
        });
    },
    actualizarEmpresas() {
      get("tablaEmpresa").then((data) => {
        this.empresas = data;
      });
    },
    async mostrarEmpresa(codigo) {
      const empresa = await get("empresas/" + codigo);
      this.RazonSocial = empresa.RazonSocial;
      this.RUC = empresa.RUC;
      this.Facebook = empresa.Facbook;
      this.Instagram = empresa.Instagram;
      this.Youtube = empresa.Youtube;
      this.Whatsapp = empresa.Whatsapp;
      this.Correo = empresa.Correo;
      this.Logo = this.ver ? logos + empresa.Logo : null;
    },
  },
  created() {
    this.actualizarEmpresas();
  },
};
</script>

<style>
</style>