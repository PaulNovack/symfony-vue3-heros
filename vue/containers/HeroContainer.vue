<template>
  <div class="w-full min-w-full">
    <div>
      <!-- Pass the searchTerm to SearchText -->
      <SearchText
        :externalValue="searchTerm"
        @search="debouncedSearch"
        @getAll="getAllSearch"
      />
    </div>

    <!-- AddFavorite Component -->
    <div class="flex flex-col space-y-4 w-full pt-2">
      <div v-if="addFavoriteError" class="text-red-500">
        {{ addFavoriteError }}
      </div>
      <AddFavorite @favoriteAdded="handleFavoriteAdded" />

      <!-- FavoriteDropdown Component -->
      <FavoriteDropdown
        :favorites="favorites"
        @favoriteSelected="handleFavoriteSelected"
        @favoriteRemoved="handleFavoriteRemove"
      />
    </div>

    <!-- Button to add selected heroes to favorite -->
    <button
      v-if="AddToFavoriteHeros.length > 0"
      @click="addSelectedHeroesToFavorite"
      id="add-heroes-button"
      class="bg-red-500 hover:bg-red-700 text-white text-base py-2 px-4 rounded-md shadow-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 whitespace-nowrap"
    >
      Add Selected Heroes to {{ favoriteListName }} favorite list
    </button>

    <!-- HeroGrid Component with event listeners for adding/removing favorites -->
    <HeroGrid
      :action-type="actionType"
      :heros="heros"
      :loading="loading"
      :limit="limit"
      :selectedFavorites="AddToFavoriteHeros"
      @addFavorite="HandleHeroChecked"
      @removeFavorite="handleHeroRemove"
    />

    <div v-if="error" class="text-red-500 mt-4">
      {{ error }}
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, onMounted, ref } from "vue";
import {
  fetchMarvelCharacterById,
  fetchMarvelCharacters,
} from "../services/marvelApiService";
import { MarvelCharacter } from "../interfaces/marvelApiTypes.ts";
import SearchText from "../components/SearchText.vue";
import AddFavorite from "../components/AddFavorite.vue";
import FavoriteDropdown from "../components/FavoriteDropdown.vue";
import HeroGrid from "../components/HeroGrid.vue";
import { Favorite } from "../interfaces/favoriteApiTypes.ts";
import {
  addHeroesToFavorite,
  createFavorite,
  deleteFavorite,
  getFavorites,
  removeHeroFromFavorite,
} from "../services/favoriteApiService.ts";
import { ActionType } from "../interfaces/uiTypes.ts";

// Utility to debounce a function
const debounce = <T extends (...args: unknown[]) => void>(
  func: T,
  wait: number,
): ((...args: Parameters<T>) => void) => {
  let timeout: number | null = null;
  return (...args: Parameters<T>) => {
    if (timeout !== null) {
      clearTimeout(timeout);
    }
    timeout = window.setTimeout(() => {
      func(...args);
    }, wait);
  };
};

export default defineComponent({
  components: {
    SearchText,
    AddFavorite,
    FavoriteDropdown,
    HeroGrid,
  },
  setup() {
    const heros = ref<MarvelCharacter[]>([]);
    const loading = ref(false);
    const error = ref("");
    const limit = ref<number>(100);
    const addFavoriteError = ref("");
    const searchTerm = ref("");
    const selectedFavoriteHeroIds = ref<number[]>([]);
    const favorites = ref<Favorite[]>([]);
    const actionType = ref<ActionType>(ActionType.Search);
    const AddToFavoriteHeros = ref<number[]>([]);
    const favoriteListName = ref<string>("Select a favorite list");
    const selectedFavoriteId = ref<number | null>(null);

    // Utility function for standardized error handling
    const handleError = (err: unknown, defaultMessage: string): string => {
      return err instanceof Error ? err.message : defaultMessage;
    };

    // Fetch heroes from Marvel API
    const fetchHeroes = async (term = "") => {
      if(term != ''){
        loading.value = true;
        error.value = "";
        heros.value = [];
        try {
          const params = { limit: limit.value, ...{ nameStartsWith: term } };
          const response = await fetchMarvelCharacters(params);
          heros.value = response.data.results;
        } catch (err) {
          error.value = `Error fetching heroes: ${handleError(err, "An unknown error occurred fetching heroes.")}`;
        } finally {
          loading.value = false;
        }
      }
    };

    const fetchAllHeroes = async () => {
      const batchSize = 10; // Set the batch size limit
      const heroIds = selectedFavoriteHeroIds.value;
      let heroResults: MarvelCharacter[] = [];

      try {
        for (let i = 0; i < heroIds.length; i += batchSize) {
          const batch = heroIds.slice(i, i + batchSize); // Create a batch of hero IDs
          const heroPromises = batch.map((heroId) =>
            fetchMarvelCharacterById(heroId).then(
              (response) => response.data.results[0],
            ),
          );
          const batchResults = await Promise.all(heroPromises); // Fetch the heroes for this batch
          heroResults = [...heroResults, ...batchResults]; // Accumulate the results
        }

        heros.value = [...heros.value, ...heroResults]; // Update the heroes state with all the results
      } catch (err) {
        error.value = `Error fetching heroes: ${handleError(err, "Failed to fetch heroes.")}`;
      }
    };

    const fetchFavoriteHeroes = async () => {
      loading.value = true;
      error.value = "";
      heros.value = [];
      try {
        await fetchAllHeroes();
      } catch (err) {
        error.value = `Error fetching heroes: ${handleError(err, "An unknown error occurred fetching heroes.")}`;
      } finally {
        loading.value = false;
      }
    };

    const debouncedSearch = debounce((term: string) => {
      handleSearch(term);
    }, 750);

    const fetchFavorites = async () => {
      try {
        favorites.value = await getFavorites();
      } catch (err) {
        error.value = `Error fetching favorites: ${handleError(err, "An unknown error occurred.")}`;
      }
    };

    const handleSearch = (term: string) => {
      addFavoriteError.value = "";
      searchTerm.value = term;
      actionType.value = ActionType.Search;
      fetchHeroes(term);
    };

    const handleFavoriteAdded = async (favorite: Favorite): Promise<void> => {
      if (
        favorites.value.some(
          (f) => f.name.toLowerCase() === favorite.name.toLowerCase(),
        )
      ) {
        addFavoriteError.value = `The favorite name "${favorite.name}" already exists.`;
        return;
      }
      try {
        await createFavorite({ name: favorite.name });
        favorites.value = await getFavorites();
      } catch (err) {
        addFavoriteError.value = `Error adding favorite: ${handleError(err, "An unknown error occurred.")}`;
      }
    };

    const getHeroIdsForFavorite = (
      favorites: Favorite[],
      favoriteId: number,
    ): number[] => {
      const favorite = favorites.find((fav) => fav.id === favoriteId);
      return favorite ? favorite.heroes.map((hero) => hero.id) : [];
    };

    const handleFavoriteSelected = (item: Favorite): void => {
      addFavoriteError.value = "";
      searchTerm.value = item.value ? item.value : "";
      favoriteListName.value = item.name;
      selectedFavoriteId.value = item.id;
      selectedFavoriteHeroIds.value = getHeroIdsForFavorite(
        favorites.value,
        selectedFavoriteId.value,
      );
      actionType.value = ActionType.Favorite;
      loading.value = true;
      heros.value = [];
      fetchFavoriteHeroes();
    };

    const handleFavoriteRemove = async (index: number): Promise<void> => {
      try {
        const favoriteToRemove = favorites.value[index];
        await deleteFavorite(favoriteToRemove.id || 0);
        favorites.value.splice(index, 1);
      } catch (err) {
        error.value = `Error removing favorite: ${handleError(err, "An unknown error occurred.")}`;
      }
    };

    const HandleHeroChecked = ({
      heroId,
      isChecked,
    }: {
      heroId: number;
      isChecked: boolean;
    }) => {
      if (favoriteListName.value.trim() === "Select a favorite list") {
        addFavoriteError.value =
          "You must have a selected favorite list to add a hero.";
        return;
      }
      if (isChecked) {
        AddToFavoriteHeros.value.push(heroId);
      } else {
        const index = AddToFavoriteHeros.value.indexOf(heroId);
        if (index > -1) {
          AddToFavoriteHeros.value.splice(index, 1);
        }
      }
    };

    const handleHeroRemove = async (heroId: number) => {
      try {
        await removeHeroFromFavorite(selectedFavoriteId.value || 0, heroId);
        const favorite = favorites.value.find(
          (fav) => fav.id === selectedFavoriteId.value,
        );
        if (favorite) {
          favorite.heroes = favorite.heroes.filter(
            (hero) => hero.id !== heroId,
          );
        }
        selectedFavoriteHeroIds.value = selectedFavoriteHeroIds.value.filter(
          (id) => id !== heroId,
        );
        heros.value = heros.value.filter((hero) => hero.id !== heroId);
      } catch (err) {
        addFavoriteError.value = `Error removing Hero: ${handleError(err, "An unknown error occurred.")}`;
      }
    };

    const addSelectedHeroesToFavorite = async () => {
      if (!selectedFavoriteId.value) {
        addFavoriteError.value =
          "You must select a favorite list to add heroes.";
        return;
      }
      try {
        await addHeroesToFavorite(selectedFavoriteId.value, {
          heroes: AddToFavoriteHeros.value,
        });
        AddToFavoriteHeros.value = [];
        fetchFavorites();
      } catch (err) {
        error.value = `Error adding heroes to favorite: ${handleError(err, "An unknown error occurred.")}`;
      }
    };

    onMounted(() => {
      fetchFavorites();
    });

    return {
      heros,
      loading,
      error,
      selectedFavoriteHeroIds,
      addFavoriteError,
      searchTerm,
      favorites,
      AddToFavoriteHeros,
      handleSearch,
      debouncedSearch,
      handleFavoriteAdded,
      handleFavoriteSelected,
      HandleHeroChecked,
      handleFavoriteRemove,
      handleHeroRemove,
      addSelectedHeroesToFavorite,
      favoriteListName,
      actionType,
      limit,
    };
  },
});
</script>
