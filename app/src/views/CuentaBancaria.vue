<template>
  <v-content>
    <v-row class="pa-5 align-center">
      <v-col cols="11">
        <h2 class="font-weight-regular text-center">Cuentas Bancarias</h2>
      </v-col>
    </v-row>
    <v-dialog v-model="dialog" persistent max-width="60vw">
      <v-card>
        <v-card-title>
          <span v-if="edit" class="headline">Editar Cuenta Bancaria</span>
          <span v-else class="headline">Nueva Cuenta Bancaria</span>
        </v-card-title>
        <v-card-text>
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-row>
              <v-col>
                <v-select
                    v-model="codEntidad"
                    :items="entidades"
                    :rules="[fieldRules.required]"
                    label="ENTIDAD FINANCIERA"
                ></v-select>
              </v-col>
            </v-row>
            <v-row>
              <v-col>
                <v-text-field v-model="nombre" label="NOMBRE"></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col>
                <v-text-field v-model="numCuenta" :rules="[fieldRules.required,fieldRules.validarCuenta,fieldRules.soloNumeros]" label="NÚMERO CUENTA" required></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col>
                <v-text-field
                  v-model="cci"
                  :rules="[fieldRules.required,fieldRules.validarCCI,fieldRules.soloNumeros]"
                  label="CCI"
                  required
                ></v-text-field>
              </v-col>
            </v-row>
            
            <v-row>
              <v-col>
                <v-select
                    v-model="tipoMoneda"
                    :items="monedas"
                    :rules="[fieldRules.required]"
                    label="MONEDA"
                ></v-select>
              </v-col>
            </v-row>
            
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="dialog = false">Cancelar</v-btn>
          <v-btn
            :loading="saveLoading"
            :disabled="saveLoading"
            color="primary"
            class="ma-2 white--text"
            depressed
            @mousedown="Validar"
            @click="opcionesRegistro"
          >Guardar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <entity-table
          :headers="headers"
          :options="options"
          :withOptions="true"
          :register = "abrirModal"
          ref="cuentasTable"
          entity="cuentasbancarias"
        />
  </v-content>
</template>

<script>
import { get, post, put, patch } from "../api/api";
import { Toast } from "../plugins/toast";
import Swal from "sweetalert2";

export default {
  components: {
    EntityTable: () => import("../components/EntityTable"),
  },
  data() {
    return {
      edit: false,
      valid: true,
      saveLoading: false,
      dialog: false,
      fieldRules: {
        required: (v) => !!v || "Campo requerido",
        validarCCI : (v) => v.length == 20 || "CCI incorrecto",
        validarCuenta: (v) => v.length > 10 && v.length < 16 || "Cuenta incorrecta",
        soloNumeros: (v) => {
            const pattern = /^[0-9]$/;
            return pattern.test(v) || "Solo numeros";
        }
      },
      headers: [
        { text: "Número", value: "correlativo" },
        { text: "Nombre", value: "nombre" },
        { text: "Entidad", value: "siglas" },
        { text: "N°. Cuenta", value: "numeroCuenta" },   
        { text: "CCI", value: "CCI" },
             
        { text: "Acciones", value: "actions", sortable: false },
      ],
      options: [
        {
          name: "Editar",
          icon: "mdi-pencil",
          function: this.verCuentaBancaria,
        },
        {
          name: "InHabilitar",
          icon: "mdi-delete-forever",
          function: this.eliminarCuenta,
        },
      ],
      entidades : [],
      monedas : [
          {value: 'S', text:"Soles"},
          {value: 'D', text: "Dolares"}
        ],
      codEmpresa: 0,
      codEntidad: 0,
      cci: "",
      numCuenta: "",
      tipoMoneda: "",
      nombre: "",
      CodigoEdit: null,
    };
  },
  methods: {
    abrirModal() {
      this.dialog = true;
    },
    reset () {
        this.$refs.form.reset()
    },
    Validar() {
      this.$refs.form.validate();
    },
    limpiar() {
      this.codEmpresa= 1;
      this.codEntidad= 0;
      this.cci= "";
      this.numCuenta= "";
      this.tipoMoneda= "";
      this.nombre= "";
      this.CodigoEdit = null;
      this.edit = false;
      this.reset();

    },

    opcionesRegistro() {
      if (this.edit) {
        this.editarCuenta();
      } else {
        this.registrarCuenta();
      }
    },
    getCuenta() {
      return {
        codEmpresa: 1,
        codEntidad: this.codEntidad,
        cci: this.cci,
        numCuenta: this.numCuenta,
        tipoMoneda: this.tipoMoneda,
        nombre: this.nombre,
      };
    },
    registrarCuenta() {
      if (this.valid == false) return;
      this.saveLoading = true;
      post("cuentasbancarias", this.getCuenta())
        .then(() => {
          Swal.fire({
            position: "top-center",
            title: "Sistema",
            text: "Cuenta Bancaria Registrada !",
            icon: "success",
            confirmButtonText: "Ok",
            timer: 2500,
          });
          this.saveLoading = false;
          this.dialog = false;
          this.$refs.cuentasTable.fetchData();
          this.limpiar();
        })
        .catch(() => {
          Toast.fire("No se Registro Cuenta Bancaria ", "", "error");
          this.saveLoading = false;
        });
    },
    editarCuenta() {
      if (this.valid == false) return;
      this.saveLoading = true;
      put("cuentasbancarias/" + this.CodigoEdit, this.getCuenta())
        .then(() => {
          Swal.fire({
            position: "top-center",
            title: "Sistema",
            text: "Cuenta Bancaria actualizada ",
            icon: "success",
            confirmButtonText: "Ok",
            timer: 2500,
          });
          this.saveLoading = false;
          this.CodigoEdit = null;
          this.dialog = false;
          this.$refs.cuentasTable.fetchData();
          this.limpiar();
        })
        .catch(() => {
          Toast.fire("No se Actualizo la Cuenta Bancaria", "", "error");
          this.saveLoading = false;
        });
    },

    verCuentaBancaria(cuenta) {
      
      this.edit = true;
      this.CodigoEdit = cuenta.Codigo;
      this.codEmpresa = cuenta.CodigoEmpresa;
      this.codEntidad = cuenta.CodigoEntidadFinanciera;
      this.cci = cuenta.CCI;
      this.numCuenta = cuenta.numeroCuenta;
      this.tipoMoneda = cuenta.tipoMoneda;
      this.nombre = cuenta.nombre;
      this.dialog = true;
    },


    eliminarCuenta(cuenta) {
      patch("cuentasbancarias/" + cuenta.Codigo).then((data) => {
        if(data.vigencia == 1){
          Swal.fire({
            position: "top-center",
            title: "Sistema",
            text: "Cuenta Bancaria Habilitada con exito",
            icon: "success",
            confirmButtonText: "Ok",
            timer: 2500,
          });
        }else{
          Swal.fire({
            position: "top-center",
            title: "Sistema",
            text: "Cuenta Bancaria Inahilitada con exito ",
            icon: "success",
            confirmButtonText: "Ok",
            timer: 2500,
          });
        }
        this.$refs.cuentasTable.fetchData();
      });
    },
  },
  created() {

    get("entidades").then((response) => {
        response.forEach((entidad) => {
            this.entidades.push({ text: entidad.siglas, value: entidad.Codigo });
        });
    }).catch((data)=>{
      console.log(data);
    })

  },
};
</script>
