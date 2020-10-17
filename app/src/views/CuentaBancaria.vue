<template>
  <v-content>
    <v-row class="pa-5 align-center">
      <v-col cols="1">&nbsp;</v-col>
      <v-col cols="1">
        <v-btn
            color="blue"
            @click="abrirModal"
            class="mx-2"
            fab
            large
            dark
          >
            <v-icon>mdi-plus</v-icon>
        </v-btn>
      </v-col>
      <v-col cols="10">
        <h2 class="font-weight-bold text-center">Cuentas Bancarias</h2>
      </v-col>
    </v-row>
    <v-dialog v-model="dialog" persistent max-width="60vw">
      <v-card>
        <v-card-title class="headline indigo darken-4"> 
          <span v-if="edit" class="headline" style="color:white;">Editar Cuenta Bancaria</span>
          <span v-else class="headline" style="color:white;">Nueva Cuenta Bancaria</span>
        </v-card-title>
        <v-card-text>
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-row>
              <v-col cols="5">
                <v-select
                    v-model="codEntidad"
                    :items="entidades"
                    :rules="[fieldRules.required]"
                    label="Entidad Bancaria"
                ></v-select>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="5">
                <v-text-field v-model="nombre" label="Nombre"></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="5">
                <v-text-field v-model="numCuenta" :rules="[fieldRules.required,fieldRules.validarCuenta]" label="Número Cuenta" required></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="5">
                <v-text-field
                  v-model="cci"
                  :rules="[fieldRules.required,fieldRules.validarCCI]"
                  label="CCI"
                  required
                ></v-text-field>
              </v-col>
            </v-row>
            
            <v-row>
              <v-col cols="5">
                <v-select
                    v-model="tipoMoneda"
                    :items="monedas"
                    :rules="[fieldRules.required]"
                    label="Moneda"
                ></v-select>
              </v-col>
            </v-row>
            
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="indigo darken-4" text @click="dialog = false">Cancelar</v-btn>
          <v-btn
            :loading="saveLoading"
            :disabled="saveLoading"
            color="indigo darken-4"
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
        validarCuenta: (v) => v.length > 10 && v.length < 16 || "Cuenta Incorrecta",

      },
      headers: [
        { text: "N°", value: "correlativo", sortable: false, class:'light blue darken-4 white--text'  },
        { text: "Nombre", value: "Nombre", sortable: false, class:'light blue darken-4 white--text'  },
        { text: "Entidad", value: "Siglas", sortable: false, class:'light blue darken-4 white--text'  },
        { text: "Número de Cuenta", value: "NumeroCuenta", sortable: false, class:'light blue darken-4 white--text'  },   
        { text: "CCI", value: "CCI", sortable: false, class:'light blue darken-4 white--text'  },
             
        { text: "Acciones", value: "actions", sortable: false, class:'light blue darken-4 white--text' },
      ],
      options: [
        {
          name: "Editar",
          icon: "mdi-border-color",
          function: this.verCuentaBancaria,
        },
        {
          name: "InHabilitar",
          icon: "mdi-checkbox-marked-circle-outline",
          function: this.eliminarCuenta,
        },
      ],
      entidades : [],
      monedas : [
          {value: 'S', text:"Soles"},
          {value: 'D', text: "Dólares"}
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
      this.limpiar();
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
      //this.reset();

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
            text: "Cuenta bancaria registrada !",
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
          Toast.fire("No se registró cuenta bancaria ", "", "error");
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
            text: "Cuenta bancaria actualizada !",
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
          Toast.fire("No se actualizó la cuenta bancaria", "", "error");
          this.saveLoading = false;
        });
    },

    verCuentaBancaria(cuenta) {
      console.log(cuenta);
      this.edit = true;
      this.CodigoEdit = cuenta.Codigo;
      this.codEmpresa = cuenta.CodigoEmpresa;
      this.codEntidad = cuenta.CodigoEntidadBancaria;
      this.cci = cuenta.CCI;
      this.numCuenta = cuenta.NumeroCuenta;
      this.tipoMoneda = cuenta.TipoMoneda;
      this.nombre = cuenta.Nombre;
      this.dialog = true;
    },


    eliminarCuenta(cuenta) {
      patch("cuentasbancarias/" + cuenta.Codigo).then((data) => {
        if(data.Vigencia == 1){
          Swal.fire({
            position: "top-center",
            title: "Sistema",
            text: "Cuenta bancaria habilitada con éxito",
            icon: "success",
            confirmButtonText: "Ok",
            timer: 2500,
          });
        }else{
          Swal.fire({
            position: "top-center",
            title: "Sistema",
            text: "Cuenta bancaria inhailitada con éxito ",
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
            this.entidades.push({ text: entidad.Siglas, value: entidad.Codigo });
        });
    }).catch((data)=>{
      console.log(data);
    })

  },
};
</script>
