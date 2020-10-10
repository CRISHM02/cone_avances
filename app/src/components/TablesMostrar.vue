<template>
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
        <v-data-table
          :loading="tableLoading"
          :headers="headers"
          :items="clients"
          :search="search"
        >
          <template v-if="withOptions" v-slot:[`item.actions`]="{ item }">
            <v-icon
              v-for="option in options"
              :key="option.name + item[headers[0].value]"
              class="mr-2"
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
  props: ["entity", "headers", "options", "withOptions"],
  data() {
    return {
      search: "",
      clients: [],
      tableLoading: true,
    };
  },
  methods: {
    fetchData() {
      this.tableLoading = true;
      get(this.entity).then((data) => {
        this.tableLoading = false;
        this.clients = data;
      });
    },
  },
  created() {
    this.fetchData();
  },
};
</script>