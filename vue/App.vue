<script lang="ts">
import { ref } from "vue";
import { useRoute } from "vue-router";

export default {
  setup() {
    // Reactive state for mobile menu visibility
    const isMobileMenuOpen = ref(false);

    // Toggle mobile menu
    const toggleMobileMenu = () => {
      isMobileMenuOpen.value = !isMobileMenuOpen.value;
    };

    // Get the current route
    const route = useRoute();

    // Define menu options as an array of objects
    const menuItems = [

      { name: "Home", path: "/" },
      { name: "Hero Listing", path: "/heroes" },
      { name: "About", path: "/about" },
    ];

    return {
      isMobileMenuOpen,
      toggleMobileMenu,
      route,
      menuItems, // Return the menu items for use in the template
    };
  },
};
</script>
<template>
  <!-- Main Menu -->
  <nav class="bg-red-500 text-white px-4 py-3 w-full fixed top-0 left-0 z-50">
    <div class="container flex justify-between items-center mx-auto">
      <div class="text-xl font-bold">Marvel Heroes</div>

      <!-- Mobile menu button -->
      <div class="block lg:hidden">
        <button
            @click="toggleMobileMenu"
            class="my-0 text-xl text-red-900 focus:outline-none"
        >
          ☰
        </button>
      </div>

      <!-- Desktop menu items -->
      <div class="hidden lg:flex space-x-4">
        <a
            v-for="item in menuItems"
            :key="item.path"
            :href="item.path"
            :class="[
            'hover:bg-red-700 px-3 py-2 rounded-md',
            route.path === item.path ? 'bg-red-700 text-black' : 'text-white',
          ]"
        >{{ item.name }}</a>
      </div>
    </div>

    <!-- Mobile menu -->
    <div v-if="isMobileMenuOpen" class="lg:hidden">
      <a
          v-for="item in menuItems"
          :key="item.path"
          :href="item.path"
          :class="[
          'block px-4 py-2 hover:bg-red-700',
          route.path === item.path ? 'bg-red-700 text-black' : 'text-white',
        ]"
      >{{ item.name }}</a>
    </div>
  </nav>

  <div class="pt-16 h-screen w-full">
    <RouterView />
  </div>
</template>
