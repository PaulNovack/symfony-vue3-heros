<template>
  <div class="flex flex-col space-y-2 w-full">
    <!-- Error message field -->
    <div v-if="errorMessage" class="text-red-500">{{ errorMessage }}</div>

    <!-- Input for Adding a New Favorite -->
    <div
      class="flex flex-col space-y-2 w-full md:flex-row md:space-y-0 md:space-x-2"
    >
      <input
        v-model="newFavorite"
        type="text"
        id="new-favorite-input"
        placeholder="Name this new favorite list"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-base rounded-lg focus:ring-red-500 focus:border-red-500 block w-full lg:max-w-[33%] p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500"
      />

      <!-- Add Favorite Button -->
      <button
        id="add-new-favorite-button"
        type="button"
        @click="addFavorite"
        class="bg-red-500 hover:bg-red-700 text-white text-sm py-1.5 px-4 rounded-md shadow-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 whitespace-nowrap"
      >
        Add New Favorite List
      </button>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";
import { Favorite } from "../interfaces/favoriteApiTypes.ts";

export default defineComponent({
  emits: ["favoriteAdded"],
  setup(props, { emit }) {
    const newFavorite = ref<string>("");
    const errorMessage = ref<string>("");

    const addFavorite = () => {
      if (newFavorite.value.trim() !== "") {
        const newItem: Favorite = {
          id: null,
          name: newFavorite.value,
          value: "Junk",
          created_at: null,
        };
        emit("favoriteAdded", newItem);
        newFavorite.value = ""; // Clear input
        errorMessage.value = ""; // Clear error message
      } else {
        errorMessage.value = "Please enter a valid favorite it cannot be blank"; // Set error message
      }
    };

    return {
      newFavorite,
      errorMessage,
      addFavorite,
    };
  },
});
</script>

<style scoped>
button {
  font-size: 1rem;
}
</style>
