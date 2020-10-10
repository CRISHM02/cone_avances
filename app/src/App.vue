<template>
  <v-app>
    <v-app-bar app color="indigo darken-4" dark>
      <v-app-bar-nav-icon @click="drawer = true"></v-app-bar-nav-icon>
      <div class="d-flex align-center"></div>
      <h2>Bienvenido</h2>
      <v-spacer></v-spacer>
    </v-app-bar>
    <v-navigation-drawer v-model="drawer" app temporary>
      <v-list-item>
        <v-list-item-content>
          <v-list-item-title class="title">Aplicacion</v-list-item-title>
          <v-list-item-subtitle></v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>

      <v-divider></v-divider>

      <v-list dense nav>
        <v-list-item to="/" link>
          <v-list-item-icon>
            <v-icon>mdi-view-dashboard</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>Inicio</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/empresa" link>
          <v-list-item-icon>
            <v-icon>mdi-view-dashboard</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>Empresa</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item to="/cuenta_bancaria" link>
          <v-list-item-icon>
            <v-icon>mdi-view-dashboard</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>Cuenta Bancaria</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
    <v-main>
      <!-- <v-btn color="success" @click="cambiarTitulo" >Cambiar titulo</v-btn> -->
      <router-view />
    </v-main>
  </v-app>
</template>

<script>
export default {
  name: "App",

  components: {},

  data: () => ({
    drawer: true,
  }),
  methods: {},
};
</script>
