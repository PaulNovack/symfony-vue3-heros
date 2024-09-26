<template>
  <div class="home-container">
    <div class="hero-section">
      <h2 class="text-red-500 text-5xl font-bold">
        Your Marvel Heroes Favorites
      </h2>
      <p class="hero-subtitle">The code for this web application can be found at <a href="https://github.com/PaulNovack/symfony-vue3-heros" target="_blank">https://github.com/PaulNovack/symfony-vue3-heros</a></p>
      <p class="hero-subtitle">
        Discover and organize your favorite Marvel Heroes.
      </p>
    </div>

    <div class="instructions-section">
      <h2 class="instructions-title">How to Use:</h2>
      <div class="instructions">
        <p class="instruction-item">
          <strong>Search Heroes:</strong> Enter the first couple of characters
          of the hero's name to find them.
        </p>
        <p class="instruction-item">
          <strong>Create Favorite Lists:</strong> Add a favorite list by
          entering a name and clicking "Add New Favorite List."
        </p>
        <p class="instruction-item">
          <strong>Manage Heroes:</strong> After creating a favorite list, select
          your newly created favorites list, search for heroes and add them to
          your list.
        </p>
        <p class="instruction-item">
          <strong>View & Edit Favorites:</strong> Re-select your favorite list
          to view and remove any heroes you want.
        </p>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, onMounted } from "vue";
import { useRoute } from "vue-router";

export default defineComponent({
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
/* Overall page container */
.home-container {
  padding: 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
  background-color: white; /* Set background to white */
  min-height: 100vh;
  font-family: "Arial", sans-serif;
}

/* Hero section */
.hero-section {
  text-align: center;
  margin-bottom: 20px;
}

.hero-title {
  font-size: 2rem;
  color: #ff6347; /* Ensure visible text color */
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.hero-subtitle {
  font-size: 1.2rem;
  color: #333; /* Dark color for visibility */
  margin-top: 10px;
  font-weight: 400;
}

/* Instructions section */
.instructions-section {
  background-color: white; /* White background */
  color: #333; /* Dark text color */
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Add depth with shadow */
  max-width: 100%; /* Make sure it scales on mobile */
  width: 100%;
  text-align: left;
}

.instructions-title {
  font-size: 1.5rem;
  color: #ff4500; /* Orange red */
  margin-bottom: 10px;
  text-align: center;
}

.instructions {
  line-height: 1.6;
}

.instruction-item {
  font-size: 1rem;
  margin-bottom: 10px;
  padding: 10px;
  border-left: 4px solid #ff4500;
  background-color: #f9f9f9;
  color: #333; /* Ensure text is visible */
  transition: transform 0.3s ease;
}

.instruction-item:hover {
  transform: translateX(10px);
}

/* Keyframes for fade-in animation */
@keyframes fadeInDown {
  0% {
    opacity: 0;
    transform: translateY(-20px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Responsive behavior for larger screens */
@media (min-width: 768px) {
  .hero-title {
    font-size: 3rem;
  }

  .hero-subtitle {
    font-size: 1.5rem;
  }

  .instructions-title {
    font-size: 2rem;
  }

  .instruction-item {
    font-size: 1.2rem;
  }
}
</style>
