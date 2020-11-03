 <!--<template v-if="withOptions" v-slot:[`item.actions`]="{ item }">
            <v-icon
              v-for="option in entityOptions"
              :key="option.name + item[headers[0].value]"
              @click="option.function(item)"
              >{{ option.icon }}</v-icon
            >
          </template>-->
<template>
  <v-row class="justify-center">
    <v-col cols="11">
      <v-card>
        <v-card-title>
          
          <v-spacer></v-spacer>
          <!--<v-text-field
            v-model="search"
            append-icon="mdi-magnify"
            label=""
            single-line
            hide-details
          ></v-text-field>-->
        </v-card-title>
        <v-data-table
          :loading="tableLoading"
          :headers="headers"
          :items="items"
          :search="search"
          :item-class="itemFilaColor"
          
        >
          <template v-if="withOptions" v-slot:[`item.actions`]="{ item }">
            <v-tooltip bottom
              v-for="option in options"
              :key="option.name + item[headers[0].value]"
              class="mr-2"              
            >
              <template v-slot:activator="{ on, attrs }">
                <v-icon
                  v-bind="attrs"
                  v-on="on"
                  class="mr-2"
                  :color = "option.name!= 'Editar' && item.Vigencia == 0 ? 'red darken-2' : 'green darken-2'"
              @click="option.function(item)"
                  >
                  {{ option.name == "InHabilitar" ? item.Vigencia == 0 ?  "mdi-checkbox-marked-circle-outline": "mdi-close-circle-outline" : option.icon }}
                </v-icon>
              </template>
              <span>{{option.name != "Editar" ? item.Vigencia == 0 ? "Habilitar" : "Inhabilitar" : option.name }}</span>
            </v-tooltip>
            <!--<v-icon
              v-for="option in options"
              :key="option.name + item[headers[0].value]"
              class="mr-2"
              :color = "item.Vigencia == 0 ? 'red darken-2' : 'green darken-2'"
              @click="option.function(item)"
              >
                {{ option.name == "InHabilitar" ? item.Vigencia == 0 ?  "mdi-checkbox-marked-circle-outline": "mdi-close-circle-outline" : option.icon }}
              </v-icon
            >-->
          </template>
        </v-data-table>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
import { get } from "../api/api";
export default {
  props: ["entity", "headers", "options", "withOptions"],
  data() {
    return {
      search: "",
      items: [],
      tableLoading: true,
    };
  },
  methods: {
    itemFilaColor: function (item) {
      return item.Vigencia ? "black--text" : "red--text";
    },
    
    fetchData() {
      this.tableLoading = true;
      var index = 1;
      this.items = [];
      get(this.entity).then((data) => {
        this.tableLoading = false;
        for (const iterator of data) {   
            var aux = Object.defineProperty(iterator,'correlativo',{ value: index });  
            this.items.push(aux);
            index ++;
        }
      });
    },
  },
  created() {

    this.fetchData();
  },
};
</script>          