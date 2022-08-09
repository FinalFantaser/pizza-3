<template>
  <div
    v-show="this.$store.state.argon.layout === 'default'"
    class="min-height-300 position-absolute w-100"
    :class="`${this.$store.state.argon.darkMode ? 'bg-transparent' : 'bg-success'}`"
  />
  <aside
    class="my-3 overflow-auto border-0 sidenav navbar navbar-vertical navbar-expand-xs border-radius-xl fixed-start ms-3"
    :class="`${
      this.$store.state.argon.layout === 'landing'
        ? 'bg-transparent shadow-none'
        : ' '
    } ${this.$store.state.argon.sidebarType}`"
    id="sidenav-main"
  >
    <div class="sidenav-header">
      <i
        class="top-0 p-3 cursor-pointer fas fa-times text-secondary opacity-5 position-absolute end-0 d-none d-xl-none"
        aria-hidden="true"
        id="iconSidenav"
      ></i>
      <router-link class="m-0 navbar-brand" to="/">
          <svg width="40" height="46" viewBox="0 0 40 46" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
              <mask id="mask0_2_2" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="40" height="46">
                  <path d="M40 0H0V45.4545H40V0Z" fill="url(#pattern0)"/>
              </mask>
              <g mask="url(#mask0_2_2)">
                  <mask id="mask1_2_2" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="6" y="2" width="28" height="34">
                      <rect x="6" y="2" width="28" height="34" fill="#D9D9D9"/>
                  </mask>
                  <g mask="url(#mask1_2_2)">
                      <path d="M34 0H6V45.4545H34V0Z" fill="url(#pattern1)"/>
                      <path d="M34 0H6V45.4545H34V0Z" fill="#44AF11"/>
                  </g>
              </g>
              <defs>
                  <pattern id="pattern0" patternContentUnits="objectBoundingBox" width="1" height="1">
                      <use xlink:href="#image0_2_2" transform="scale(0.0113636 0.01)"/>
                  </pattern>
                  <pattern id="pattern1" patternContentUnits="objectBoundingBox" width="1" height="1">
                      <use xlink:href="#image0_2_2" transform="scale(0.0113636 0.01)"/>
                  </pattern>
                  <image id="image0_2_2" width="88" height="100" xlink:href="data:image/png;base64,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"/>
              </defs>
          </svg>

          <!--        <img-->
<!--          :src="-->
<!--            this.$store.state.argon.darkMode ||-->
<!--            this.$store.state.argon.sidebarType === 'bg-default'-->
<!--              ? logoWhite-->
<!--              : logo-->
<!--          "-->
<!--          class="navbar-brand-img h-100"-->
<!--          alt="main_logo"-->
<!--        />-->
        <span class="ms-2 font-weight-bold me-2">L-digital</span>
      </router-link>
    </div>
    <hr class="mt-0 horizontal dark" />
    <sidenav-list :cardBg="custom_class" />
  </aside>
</template>
<script>
import SidenavList from "./SidenavList.vue";
import logo from "@/assets/img/logo-ct-dark.png";
import logoWhite from "@/assets/img/logo-ct.png";

export default {
  name: "index",
  components: {
    SidenavList
  },
  data() {
    return {
      logo,
      logoWhite
    };
  },
  props: ["custom_class", "layout"]
};
</script>
