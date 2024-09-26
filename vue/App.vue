<script lang="ts">
import { ref } from "vue";
import { useRoute } from "vue-router"; // Import useRoute from vue-router

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

    return {
      isMobileMenuOpen,
      toggleMobileMenu,
      route, // Make the route available in the template
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
            href="/"
            :class="[
            'hover:bg-red-700 px-3 py-2 rounded-md',
            route.path === '/' ? 'bg-red-700 text-black' : 'text-white',
          ]"
        >PaulNovack.net Home</a
        >
        <a
          href="/heroes-app/"
          :class="[
            'hover:bg-red-700 px-3 py-2 rounded-md',
            route.path === '/heroes-app/' ? 'bg-red-700 text-black' : 'text-white',
          ]"
          >Home</a
        >
        <a
          href="/heroes-app/heroes"
          :class="[
            'hover:bg-red-700 px-3 py-2 rounded-md',
            route.path === '/heroes-app/heroes' ? 'bg-red-700 text-black' : 'text-white',
          ]"
          >Hero Listing</a
        >
        <a
          href="/heroes-app/about"
          :class="[
            'hover:bg-red-700 px-3 py-2 rounded-md',
            route.path === '/heroes-app/about' ? 'bg-red-700 text-black' : 'text-white',
          ]"
          >About</a
        >
      </div>
    </div>

    <!-- Mobile menu -->
    <div v-if="isMobileMenuOpen" class="lg:hidden">
      <a
        href="/"
        :class="[
          'block px-4 py-2 hover:bg-red-700',
          route.path === '/' ? 'bg-red-700 text-black' : 'text-white',
        ]"
        >Home</a
      >
      <a
        href="/heros"
        :class="[
          'block px-4 py-2 hover:bg-red-700',
          route.path === '/heros' ? 'bg-red-700 text-black' : 'text-white',
        ]"
        >Hero Listing</a
      >
      <a
        href="/about"
        :class="[
          'block px-4 py-2 hover:bg-red-700',
          route.path === '/about' ? 'bg-red-700 text-black' : 'text-white',
        ]"
        >About</a
      >
    </div>
  </nav>

  <div class="pt-16 h-screen w-full">
    <RouterView />
  </div>
</template>
