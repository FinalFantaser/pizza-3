<!--
=========================================================
* Vue Argon Dashboard 2 - v3.0.0
=========================================================

* Product Page: https://creative-tim.com/product/vue-argon-dashboard
* Copyright 2022 Creative Tim (https://www.creative-tim.com)

Coded by www.creative-tim.com

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<template>
  <div
    v-show="this.$store.state.argon.layout === 'landing'"
    class="landing-bg h-100 bg-gradient-primary position-fixed w-100"
  ></div>
  <sidenav
    :custom_class="this.$store.state.argon.mcolor"
    :class="[
      this.$store.state.argon.isTransparent
    ]"
    v-if="this.$store.state.argon.showSidenav"
  />
  <main
    class="main-content position-relative max-height-vh-100 h-100 border-radius-lg"
  >
    <!-- nav -->
    <navbar
      :class="[navClasses]"
      :textWhite="
        this.$store.state.argon.isAbsolute ? 'text-white opacity-8' : 'text-white'
      "
      :minNav="navbarMinimize"
      v-if="this.$store.state.argon.showNavbar"
    />
    <router-view />
    <app-footer v-show="this.$store.state.argon.showFooter" />
  </main>
    <loader v-if="this.stateLoader"></loader>
</template>
<script>
import Loader from "./examples/Loader.vue"
import Sidenav from "./examples/Sidenav/index.vue";
import Navbar from "@/examples/Navbars/Navbar.vue";
import AppFooter from "@/examples/Footer.vue";
import { mapMutations } from "vuex";

export default {
  name: "App",
  components: {
    Sidenav,
    Navbar,
    AppFooter,
    Loader,
  },
  methods: {
    ...mapMutations(["toggleConfigurator", "navbarMinimize"])
  },
  computed: {
      stateLoader() {
        return this.$store.getters.stateLoader
      },
    navClasses() {
      return {
        "position-sticky bg-white left-auto top-2 z-index-sticky":
          this.$store.state.argon.isNavFixed && !this.$store.state.argon.darkMode,
        "position-sticky bg-default left-auto top-2 z-index-sticky":
          this.$store.state.argon.isNavFixed && this.$store.state.argon.darkMode,
        "position-absolute px-4 mx-0 w-100 z-index-2": this.$store.state
          .isAbsolute,
        "px-0 mx-4": !this.$store.state.argon.isAbsolute
      };
    }
  },
  beforeMount() {
    this.$store.state.argon.isTransparent = "bg-transparent";
  }
};
</script>
