<template>
  <nav
    class="navbar navbar-main navbar-expand-lg px-0 mx-0 mx-sm-4 shadow-none"
    id="navbarBlur"
  >
    <div class="container-fluid p-0 justify-content-between ">
        <breadcrumbs :currentPage="currentRouteName" textWhite="text-white" />

        <div class="d-flex flex-column align-items-center mx-2">
          <h6 class="mb-0 text-white">
              <i style="color: yellow;" class="fa fa-sun-o" aria-hidden="true"></i> / <i class="fa fa-moon-o text-dark" aria-hidden="true"></i>
          </h6>
          <div class="form-check form-switch p-0">
            <input
              class="form-check-input mt-1 ms-auto"
              type="checkbox"
              :checked="this.$store.state.argon.darkMode"
              @click="setDarkMode"
            />
          </div>
        </div>

        <div class="d-flex flex-column-reverse flex-md-row">

            <div class="d-flex align-items-center me-md-3">
              <div class="input-group">
                <span class="input-group-text text-body">
                  <i class="fas fa-search" aria-hidden="true"></i>
                </span>
                <input
                  type="text"
                  class="form-control"
                  placeholder="Поиск"
                />
              </div>
            </div>

            <div class="d-flex align-items-start justify-content-end">
              <div class="nav-item align-items-center">
                  <a
                      @click.prevent="$store.dispatch('logout')"
                      href="#"
                      class="p-0 nav-link font-weight-bold text-white d-flex flex-column align-items-center">
                      <i class="fa fa-user"></i><span>выйти</span>
                  </a>
              </div>
              <div @click="toggleSidebar" class="px-3 nav-item d-flex align-items-center">
                <a class="p-0 nav-link text-white">
                  <i class="cursor-pointer fa fa-cog fixed-plugin-button-nav"></i>
                </a>
              </div>
              <div
                class="nav-item dropdown d-flex align-items-center pe-2"
              >
                <a
                  href="#"
                  class="p-0 nav-link text-white"
                  :class="[showMenu ? 'show' : '']"
                  id="dropdownMenuButton"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                  @click="showMenu = !showMenu"
                >
                  <i class="cursor-pointer fa fa-bell"></i>
                </a>
                <ul
                  class="px-2 py-3 dropdown-menu dropdown-menu-end me-sm-n4"
                  :class="showMenu ? 'show' : ''"
                  aria-labelledby="dropdownMenuButton"
                >
                  <li class="mb-2">
                    <a class="dropdown-item border-radius-md" href="javascript:;">
                      <div class="py-1 d-flex">
                        <div class="my-auto">
                          <img
                            src="@/assets/img/team-2.jpg"
                            class="avatar avatar-sm me-3"
                            alt="user image"
                          />
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-1 text-sm font-weight-normal">
                            <span class="font-weight-bold">New message</span> from
                            Laur
                          </h6>
                          <p class="mb-0 text-xs text-secondary">
                            <i class="fa fa-clock me-1"></i>
                            13 minutes ago
                          </p>
                        </div>
                      </div>
                    </a>
                  </li>
                  <li class="mb-2">
                    <a class="dropdown-item border-radius-md" href="javascript:;">
                      <div class="py-1 d-flex">
                        <div class="my-auto">
                          <img
                            src="@/assets/img/small-logos/logo-spotify.svg"
                            class="avatar avatar-sm bg-gradient-dark me-3"
                            alt="logo spotify"
                          />
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-1 text-sm font-weight-normal">
                            <span class="font-weight-bold">New album</span> by
                            Travis Scott
                          </h6>
                          <p class="mb-0 text-xs text-secondary">
                            <i class="fa fa-clock me-1"></i>
                            1 day
                          </p>
                        </div>
                      </div>
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item border-radius-md" href="javascript:;">
                      <div class="py-1 d-flex">
                        <div
                          class="my-auto avatar avatar-sm bg-gradient-secondary me-3"
                        >
                          <svg
                            width="12px"
                            height="12px"
                            viewBox="0 0 43 36"
                            version="1.1"
                            xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink"
                          >
                            <title>credit-card</title>
                            <g
                              stroke="none"
                              stroke-width="1"
                              fill="none"
                              fill-rule="evenodd"
                            >
                              <g
                                transform="translate(-2169.000000, -745.000000)"
                                fill="#FFFFFF"
                                fill-rule="nonzero"
                              >
                                <g transform="translate(1716.000000, 291.000000)">
                                  <g transform="translate(453.000000, 454.000000)">
                                    <path
                                      class="color-background"
                                      d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z"
                                      opacity="0.593633743"
                                    />
                                    <path
                                      class="color-background"
                                      d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z"
                                    />
                                  </g>
                                </g>
                              </g>
                            </g>
                          </svg>
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-1 text-sm font-weight-normal">
                            Payment successfully completed
                          </h6>
                          <p class="mb-0 text-xs text-secondary">
                            <i class="fa fa-clock me-1"></i>
                            2 days
                          </p>
                        </div>
                      </div>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
        </div>
    </div>
  </nav>
</template>
<script>
import Breadcrumbs from "../Breadcrumbs.vue";
import { mapMutations, mapActions } from "vuex";
import { activateDarkMode, deactivateDarkMode } from "@/assets/js/dark-mode";

export default {
  name: "navbar",
  data() {
    return {
      showMenu: false
    };
  },
  props: ["minNav", "textWhite"],
  created() {
    this.minNav;
  },
  methods: {
    ...mapMutations(["navbarMinimize", "toggleConfigurator"]),
    ...mapActions(["toggleSidebarColor"]),

    toggleSidebar() {
      this.toggleSidebarColor("bg-white");
      this.navbarMinimize();
    },
    setDarkMode() {
      if (this.$store.state.argon.darkMode) {
        this.$store.state.argon.darkMode = false;
        this.$store.state.argon.sidebarType = "bg-white";
        deactivateDarkMode();
        return;
      } else {
        this.$store.state.argon.darkMode = true;
        this.$store.state.argon.sidebarType = "bg-default";
        activateDarkMode();
      }
    },
  },
  components: {
    Breadcrumbs
  },
  computed: {
    currentRouteName() {
      return this.$route.name;
    }
  }
};
</script>
