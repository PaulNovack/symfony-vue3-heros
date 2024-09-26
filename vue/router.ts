import { createRouter, createWebHistory } from "vue-router";
import HomePage from "./pages/HomePage.vue";
import HeroesPage from "./pages/HeroesPage.vue";
import AboutPage from "./pages/AboutPage.vue";
const routes = [
  {
    path: "/heroes-app/",
    name: "Home",
    component: HomePage,
    meta: { title: "Home" },
  },
  {
    path: "/heroes-app/heroes",
    name: "Heroes",
    components: { default: HeroesPage },
    meta: { title: "Heroes Page" },
  },
  {
    path: "//heroes-app/about",
    name: "About",
    components: { default: AboutPage },
    meta: { title: "About this Application" },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
