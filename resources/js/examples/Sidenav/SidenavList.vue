<template>
  <div
    class="collapse navbar-collapse w-auto h-auto h-100"
    id="sidenav-collapse-main"
  >
    <ul class="navbar-nav">
      <li class="nav-item">
        <sidenav-item
          url="/"
          :class="getRoute() === 'dashboard-default' ? 'active' : ''"
          :navText="'Главная'"
        >
          <template v-slot:icon>
            <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
          </template>
        </sidenav-item>
      </li>
      <li class="nav-item">
        <sidenav-item
          url="/users"
          :class="getRoute() === 'users' ? 'active' : ''"
          :navText="'Пользователи'"
        >
          <template v-slot:icon>
            <i
              class="fa fa-users text-warning text-sm opacity-10"
            ></i>
          </template>
        </sidenav-item>
      </li>
        <li class="nav-item">
            <sidenav-item
                url="/cities"
                :class="getRoute() === '/cities' ? 'active' : ''"
                :navText="'Города'"
            >
                <template v-slot:icon>
                    <i class="ni ni-istanbul text-danger text-sm opacity-10"></i>
                </template>
            </sidenav-item>
        </li>
        <li class="nav-item">
            <sidenav-item
                url="/delivery"
                :class="getRoute() === '/delivery' ? 'active' : ''"
                :navText="'Доставка'"
            >
                <template v-slot:icon>
                    <i class="ni ni-user-run text-success text-sm opacity-10"></i>
                </template>
            </sidenav-item>
        </li>
        <li class="nav-item">
            <div class="accordion-item mx-2">
                <h2 class="p-3 accordion-header d-flex align-items-center" id="headingOne">
                    <div class="p-3 icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                        <i class="fa fa-tags text-secondary text-sm opacity-10"></i>
                    </div>
                    <button class="accordion-button collapsed p-0 ms-1 text-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Каталог
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body p-0">
                        <div class="nav-item">
                            <sidenav-item
                                url="/categories"
                                :class="getRoute() === '/categories' ? 'active' : ''"
                                :navText="'Категории'"
                            >
                                <template v-slot:icon>
                                    <i class="top-0 fa fa-angle-double-right text-secondary text-sm opacity-10"></i>
                                </template>
                            </sidenav-item>
                        </div>
                        <div class="nav-item">
                            <sidenav-item
                                url="/products"
                                :class="getRoute() === '/products' ? 'active' : ''"
                                :navText="'Продукты'"
                            >
                                <template v-slot:icon>
                                    <i class="top-0 fa fa-angle-double-right text-secondary text-sm opacity-10"></i>
                                </template>
                            </sidenav-item>
                        </div>
                        <div class="nav-item">
                            <sidenav-item
                                url="/popular"
                                :class="getRoute() === '/popular' ? 'active' : ''"
                                :navText="'Популярные товары'"
                            >
                                <template v-slot:icon>
                                    <i class="top-0 fa fa-angle-double-right text-secondary text-sm opacity-10"></i>
                                </template>
                            </sidenav-item>
                        </div>
                        <div class="nav-item">
                            <sidenav-item
                                url="/options"
                                :class="getRoute() === '/options' ? 'active' : ''"
                                :navText="'Опции'"
                            >
                                <template v-slot:icon>
                                    <i class="top-0 fa fa-angle-double-right text-secondary text-sm opacity-10"></i>
                                </template>
                            </sidenav-item>
                        </div>
                    </div>
                </div>
            </div>
        </li>

        <li class="nav-item">
            <div class="accordion-item mx-2">
                <h2 class="p-3 accordion-header d-flex align-items-center" id="paymantHeading">
                    <div class="p-3 icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                        <i class="ni ni-money-coins text-warning text-sm opacity-10"></i>
                    </div>
                    <button class="accordion-button collapsed p-0 ms-1 text-sm" type="button" data-bs-toggle="collapse" data-bs-target="#paymant" aria-expanded="true" aria-controls="paymant">
                        Оплата
                    </button>
                </h2>
                <div id="paymant" class="accordion-collapse collapse" aria-labelledby="headingOne">
                    <div class="accordion-body p-0">
                        <div class="nav-item">
                            <sidenav-item
                                url="/yookassa"
                                :class="getRoute() === '/yookassa' ? 'active' : ''"
                                :navText="'Ю-касса'"
                            >
                                <template v-slot:icon>
                                    <i class="top-0 fa fa-angle-double-right text-secondary text-sm opacity-10"></i>
                                </template>
                            </sidenav-item>
                        </div>
                    </div>
                </div>
            </div>
        </li>

        <li class="nav-item">
            <sidenav-item
                url="/posters"
                :class="getRoute() === '/posters' ? 'active' : ''"
                :navText="'Постеры'"
            >
                <template v-slot:icon>
                    <i class="ni ni-album-2 text-primary text-sm opacity-10"></i>
                </template>
            </sidenav-item>
        </li>
      <li class="nav-item">
        <sidenav-item
          url="/orders"
          :class="getRoute() === '/orders' ? 'active' : ''"
          :navText="'Заказы'"
        >
          <template v-slot:icon>
            <i class="ni ni-app text-info text-sm opacity-10"></i>
          </template>
        </sidenav-item>
      </li>
      <li class="nav-item">
        <sidenav-item
          url="/signin"
          :class="getRoute() === 'signin' ? 'active' : ''"
          :navText="'Sign In'"
        >
          <template v-slot:icon>
            <i class="ni ni-single-copy-04 text-danger text-sm opacity-10"></i>
          </template>
        </sidenav-item>
      </li>
      <li class="nav-item">
        <sidenav-item
          url="/signup"
          :class="getRoute() === 'signup' ? 'active' : ''"
          :navText="'Sign Up'"
        >
          <template v-slot:icon>
            <i class="ni ni-collection text-info text-sm opacity-10"></i>
          </template>
        </sidenav-item>
      </li>
    </ul>
  </div>
  <!-- <div class="pt-3 mx-3 mt-3 sidenav-footer">
    <sidenav-card
      :class="cardBg"
      textPrimary="Need Help?"
      textSecondary="Please check our docs"
    />
  </div> -->
</template>
<script>
import SidenavItem from "./SidenavItem.vue";
// import SidenavCard from "./SidenavCard.vue";

export default {
  name: "SidenavList",
  props: {
    cardBg: String
  },
  data() {
    return {
      title: "Argon Dashboard 2",
      controls: "dashboardsExamples",
      isActive: "active"
    };
  },
  components: {
    SidenavItem,
    // SidenavCard
  },
  methods: {
    getRoute() {
      const routeArr = this.$route.path.split("/");
      return routeArr[1];
    }
  }
};
</script>

<style scoped>

.accordion-button {
  font-size: 0.875rem;
}
.accordion-button::after {
  background: url(../../assets/img/icons/down-arrow.png) !important;
}

.dark-version .accordion-button {
    color: #fff;
    opacity: 0.8;
}

</style>
