<template>
  <div>
    <div class="mb-4 mt-4 text-gray-700 font-semibold">
      <span
        class="pr-1"
        v-if="!loading && heros.length > 0 && actionType == ActionType.Search"
        >Search Results Heroes:</span
      >
      <span
        class="pr-1"
        v-if="!loading && heros.length > 0 && actionType == ActionType.Favorite"
        >Favorite List Heroes:</span
      >
      <span v-if="!loading && heros.length > 0"
        >{{ heros.length }} heroes found</span
      >
      <span
        class="mt-8"
        v-if="
          !loading && heros.length === 0 && actionType == ActionType.Favorite
        "
        >No heroes found in Hero List</span
      >
      <span
        class="mt-8"
        v-if="!loading && heros.length === 0 && actionType == ActionType.Search"
        >No heroes found for search</span
      >
      <br />
      <span
        v-if="
          !loading && heros.length === 0 && actionType == ActionType.Favorite
        "
      >
        <br />Type something in search to get list of heroes to add or click
        "Get All" to get list of first 100 heros"
      </span>
    </div>

    <!-- Grid layout for hero tiles -->
    <div
      id="hero-parent-div"
      class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"
    >
      <div
        v-for="(hero, index) in heros"
        :key="hero.id"
        :id="'hero-div-' + index"
        class="bg-white shadow-tile rounded-lg overflow-hidden"
      >
        <!-- Conditionally show Add to Favorite List checkbox if actionType is Search -->
        <div class="p-4" v-if="actionType === ActionType.Search">
          <label class="inline-flex items-center">
            <input
              :id="'hero-checkbox-' + index"
              type="checkbox"
              :checked="isFavorite(hero.id)"
              @change="onHerosChecked($event, hero.id)"
              class="form-checkbox h-5 w-5 text-red-600"
            />
            <span class="ml-2 text-gray-700">Add to Favorite List</span>
          </label>
        </div>

        <!-- Conditionally show Remove from Favorite List link if actionType is Favorite -->
        <div class="p-4" v-if="actionType === ActionType.Favorite">
          <a
            href="#"
            :id="'hero-remove-atag-' + index"
            @click.prevent="onHeroRemove(hero.id)"
            class="text-red-600 hover:text-red-800"
          >
            Remove from Favorite List
          </a>
        </div>

        <img
          :src="hero.thumbnail.path + '.' + hero.thumbnail.extension"
          alt="Hero image"
          class="w-full object-cover"
          style="aspect-ratio: 5 / 4"
        />

        <div class="p-4">
          <span class="text-base">Character Id: {{ hero.id }}</span>
          <h3 class="text-xl font-semibold text-red-500">{{ hero.name }}</h3>
          <p class="text-gray-700 mt-2">{{ hero.description }}</p>
        </div>
      </div>

      <!-- No heroes found message
      <div v-if="!loading && heros.length === 0" class="col-span-full w-full text-center">
        <p class="text-orange-500 w-full">No heroes found.</p>
      </div>-->

      <!-- Loading message -->
      <div
        v-if="loading && heros.length === 0"
        class="col-span-full flex flex-col items-center justify-center w-full h-64"
      >
        <div class="spinner mb-4"></div>
        <p class="font-bold text-xl text-red-500">Loading Heroes...</p>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from "vue";
import { MarvelCharacter } from "../interfaces/marvelApiTypes.ts";
import { ActionType } from "../interfaces/uiTypes.ts";

export default defineComponent({
  props: {
    heros: {
      type: Array as PropType<MarvelCharacter[]>,
      required: true,
    },
    loading: {
      type: Boolean,
      required: true,
    },
    actionType: {
      type: String as PropType<ActionType>,
      required: true,
    },
    selectedFavorites: {
      type: Array as PropType<number[]>,
      default: () => [],
    },
  },
  emits: ["addFavorite", "removeFavorite"],
  setup(props, { emit }) {
    const onHerosChecked = (event: Event, heroId: number) => {
      const isChecked = (event.target as HTMLInputElement).checked;
      emit("addFavorite", { heroId, isChecked });
    };

    const onHeroRemove = (heroId: number) => {
      emit("removeFavorite", heroId);
    };

    const isFavorite = (heroId: number) => {
      return props.selectedFavorites.includes(heroId);
    };

    return {
      onHerosChecked,
      onHeroRemove,
      isFavorite,
      ActionType,
    };
  },
});
</script>

<style scoped>
.spinner {
  border: 8px solid #f3f3f3;
  border-top: 8px solid #ef4444;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.shadow-tile {
  box-shadow: 0px -3px 8px rgba(0, 0, 0, 0.1);
}
</style>
