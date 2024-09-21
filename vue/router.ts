import { createRouter, createWebHistory } from "vue-router";
import HomePage from "./pages/HomePage.vue";
import HerosPage from "./pages/HerosPage.vue";
import AboutPage from "./pages/AboutPage.vue";
const routes = [
  {
    path: "/",
    name: "Home",
    component: HomePage,
    meta: { title: "Home" },
  },
  {
    path: "/heros",
    name: "Heros",
    components: { default: HerosPage },
    meta: { title: "Heros Page" },
  },
  {
    path: "/about",
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
