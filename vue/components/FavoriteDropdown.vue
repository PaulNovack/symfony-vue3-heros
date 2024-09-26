<template>
  <div class="relative w-full md:w-3/4 lg:w-1/2 xl:w-1/2 2xl:w-1/3 pb-4 pt-0">
    <div
      id="favorite-list"
      class="bg-gray-50 border border-gray-300 text-base text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500 cursor-pointer"
      @click="toggleDropdown"
    >
      <span>{{ selected ? selected.name : "Select a favorite list" }}</span>
      <svg
        class="w-5 h-5 inline float-right"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M19 9l-7 7-7-7"
        ></path>
      </svg>
    </div>

    <!-- Dropdown List -->
    <ul
      v-if="dropdownOpen"
      class="absolute bg-white shadow-md rounded-md w-full mt-1 z-10 dark:bg-gray-700"
    >
      <li
        v-for="(favorite, index) in favorites"
        :key="favorite.value"
        class="flex text-base justify-between items-center p-2 hover:bg-gray-100 dark:hover:bg-gray-600"
      >
        <span
          :id="'favorite-id-' + index"
          @click="selectItem(favorite)"
          class="cursor-pointer"
          >{{ favorite.name }}</span
        >
        <button
          :id="'trash-' + index"
          @click="removeFavorite(index)"
          class="text-red-500 hover:text-red-700"
        >
          🗑️
        </button>
      </li>
    </ul>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";
import { Favorite } from "../interfaces/favoriteApiTypes";

export default defineComponent({
  props: {
    favorites: {
      type: Array as () => Favorite[],
      required: true,
    },
  },
  emits: ["favoriteSelected", "favoriteRemoved"],
  setup(props, { emit }) {
    const selected = ref<Favorite | null>(null);
    const dropdownOpen = ref(false);

    const toggleDropdown = () => {
      dropdownOpen.value = !dropdownOpen.value;
    };

    const selectItem = (item: Favorite) => {
      selected.value = item;
      dropdownOpen.value = false;
      emit("favoriteSelected", item);
    };

    const removeFavorite = (index: number) => {
      emit("favoriteRemoved", index);
    };

    return {
      selected,
      dropdownOpen,
      toggleDropdown,
      selectItem,
      removeFavorite,
    };
  },
});
</script>

<style scoped>
button {
  font-size: 1rem;
}
</style>
