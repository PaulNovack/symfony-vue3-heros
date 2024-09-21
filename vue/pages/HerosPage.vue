<template>
  <div class="heros">
    <HeroContainer />
  </div>
</template>

<script lang="ts">
import { defineComponent, onMounted } from "vue";
import { useRoute } from "vue-router";
import HeroContainer from "../containers/HeroContainer.vue";

export default defineComponent({
  components: {
    HeroContainer,
  },
  setup() {
    const route = useRoute();

    onMounted(() => {
      if (route.meta.title) {
        document.title = route.meta.title as string;
      }
    });
  },
});
</script>

<style scoped>
.heros {
  padding: 5px;
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 200px;
}
</style>
