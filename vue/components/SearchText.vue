<template>
  <div class="flex flex-col mt-2 space-y-2 w-full">
    <div v-if="errorMessage" class="text-red-500">{{ errorMessage }}</div>

    <div class="flex space-x-2 w-full">
      <input
        v-model="inputValue"
        type="text"
        id="search"
        name="search"
        @keyup="handleSearch"
        :placeholder="'Enter Search'"
        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-red-500 focus:border-red-500 block w-full text-base md:w-3/4 lg:w-1/2 xl:w-1/2 2xl:w-1/3 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500"
      />

      <!-- Updated Get All Button -->
      <button
        @click="handleGetAll"
        class="bg-red-500 hover:bg-red-700 text-white text-base py-1.5 px-4 rounded-md shadow-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 whitespace-nowrap"
      >
        Search to Get All
      </button>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, watch } from "vue";

export default defineComponent({
  props: {
    externalValue: {
      type: String,
      default: "",
    },
  },
  emits: ["search", "getAll"], // Emit getAll event
  setup(props, { emit }) {
    const inputValue = ref(""); // Local input value
    const errorMessage = ref("");

    // Watch for changes in the externalValue prop and update the inputValue
    watch(
      () => props.externalValue,
      (newValue) => {
        inputValue.value = newValue;
      },
      { immediate: true }, // Ensure the input is updated when the component is mounted
    );

    const handleSearch = () => {
      const value = inputValue.value.trim();
      emit("search", value); // Emit the search event with the input value
      if (value) {
        errorMessage.value = ""; // Clear error if valid
      }
    };

    const handleGetAll = () => {
      inputValue.value = ""; // Reset input field to blank
      emit("getAll"); // Emit an event to trigger fetch all
    };

    return {
      inputValue,
      errorMessage,
      handleSearch,
      handleGetAll, // Include the handleGetAll method
    };
  },
});
</script>
