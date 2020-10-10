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
          <v-btn
            color="primary"
            v-if="register"
            @click="register"
            class="mx-2"
            fab
            small
            dark
          >
            <v-icon>mdi-plus</v-icon>
          </v-btn>
          <v-spacer></v-spacer>
          <v-text-field
            v-model="search"
            append-icon="mdi-magnify"
            label=""
            single-line
            hide-details
          ></v-text-field>
        </v-card-title>
        <v-data-table
          :loading="tableLoading"
          :headers="headers"
          :items="items"
          :search="search"
        >
          <template v-if="withOptions" v-slot:[`item.actions`]="{ item }">
            <v-icon
              v-for="option in options"
              :key="option.name + item[headers[0].value]"
              class="mr-2"
              :color = "item.vigencia == 0 ? 'red darken-2' : 'green darken-2'"
              @click="option.function(item)"
              >{{ option.icon }}</v-icon
            >
          </template>
        </v-data-table>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
import { get } from "../api/api";
export default {
  props: ["entity", "headers", "options", "withOptions" ,"register"],
  data() {
    return {
      search: "",
      items: [],
      tableLoading: true,
    };
  },
  methods: {
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