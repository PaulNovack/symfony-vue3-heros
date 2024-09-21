<template>
  <div class="about">
    <h2 class="text-red-500 font-bold text-xl sm:text-2xl text-center pb-8">
      About This Application
    </h2>

    <div class="grid grid-cols-1 gap-4 sm:gap-6 lg:grid-cols-2">
      <!-- Front End Section -->
      <div class="tile p-4 bg-white shadow-tile rounded-lg overflow-hidden">
        <h3 class="font-bold text-red-500 text-lg sm:text-xl md:text-2xl">
          Frontend Architecture
        </h3>
        <p class="py-2 text-base text-black sm:text-lg">
          This project leverages modern frontend tooling, built using
          <strong>Vite</strong> for fast bundling, <strong>Vue 3</strong> with
          <strong>Vue Router</strong> for component-driven views, and
          <strong>Tailwind CSS</strong> for utility-first styling. Comprehensive
          testing is enabled with <strong>Cypress</strong>.
        </p>
        <p>
          To run successfully should have installed npm version 10.8.2 and node
          version 20.17.0 at minimum
        </p>
        <div class="text-left">
          <p class="text-sm text-black sm:text-base">
            Code formatting and linting are handled by Prettier and ESLint.
          </p>
        </div>
        <ul class="py-2 text-left text-sm text-black sm:text-base">
          <li class="py-2">
            View available frontend commands and run interactively in
            console:<br />
            <code class="block">npm run what</code>
          </li>
          <li class="py-2">
            Check formatting:<br />
            <code class="block">npm run check-format</code>
          </li>
          <li class="py-2">
            Format code:<br />
            <code class="block">npm run format-code</code>
          </li>
          <li class="py-2">
            Lint with ESLint:<br />
            <code class="block">npm run lint</code>
          </li>
          <li class="py-2">
            Automatically fix linting issues:<br />
            <code class="block">npm run lint-fix</code>
          </li>
          <li class="py-2">
            Run the development server:<br />
            <code class="block">npm run dev</code>
          </li>
        </ul>
        <p class="py-2 text-sm text-black sm:text-base">
          End-to-end (e2e) and API testing are implemented using
          <strong>Cypress</strong>.
        </p>
        <ul class="py-2 text-left text-sm text-black sm:text-base">
          <li class="py-2">
            Run tests in headless mode:<br />
            <code class="block">npm run cy:test</code>
          </li>
          <li class="py-2">
            Open Cypress interactive mode:<br />
            <code class="block">npm run cy:open</code>
          </li>
        </ul>
      </div>

      <!-- Back End Section -->
      <div class="tile p-4 bg-white shadow-tile rounded-lg overflow-hidden">
        <h3 class="font-bold text-red-500 text-lg sm:text-xl md:text-2xl">
          Backend Architecture
        </h3>
        <p class="py-2 text-base text-black sm:text-lg">
          The backend is built using <strong>Symfony</strong>, with a simple
          <strong>SQLite</strong> database to simplify local development without
          needing additional database services.
        </p>
        <div class="text-left">
          <p class="text-sm text-black sm:text-base">
            Code quality is ensured using <strong>PHP-CS-Fixer</strong> and
            <strong>PHPStan</strong>, and testing is handled with
            <strong>Pest3</strong>.
          </p>
          <p>
            To run successfully should have installed php 8.2 and all the
            necessary extensions for a symfony application.
          </p>
        </div>
        <ul class="py-2 text-left text-sm text-black sm:text-base">
          <li class="py-2">
            Check code formatting:<br />
            <code class="block">composer check</code>
          </li>
          <li class="py-2">
            Fix code formatting issues:<br />
            <code class="block">composer fix</code>
          </li>
          <li class="py-2">
            Run PHPStan analysis:<br />
            <code class="block">composer phpstan</code>
          </li>
          <li class="py-2">
            Run backend tests with Pest:<br />
            <code class="block">composer test</code>
          </li>
          <li class="py-2">
            Start the Symfony built-in server:<br />
            <code class="block">composer serve</code><br /><br />
            <span
              ><i>
                * Please ensure you access the API via
                <strong>http://localhost:8000</strong> (not
                <strong>http://127.0.0.1</strong>) to avoid CORS issues.
              </i></span
            >
          </li>
        </ul>
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
.about {
  padding: 15px;
  font-family: Arial, sans-serif;
}

h2 {
  margin-bottom: 15px;
  font-size: 1.8rem;
  text-align: center;
  color: #ff4500; /* Ensure text is colored */
}

h3 {
  margin-bottom: 10px;
  font-size: 1.5rem;
  color: #ff4500; /* Ensure heading text is colored */
}

p {
  text-align: left;
  margin-bottom: 10px;
  line-height: 1.6;
  color: #000; /* Ensure all paragraph text is black */
}

.tile {
  background-color: #ffffff;
  padding: 15px;
  border-radius: 10px;
}

.shadow-tile {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

ul {
  list-style-type: none;
  padding-left: 0;
}

li {
  margin-bottom: 10px;
}

code {
  background-color: #f4f4f4;
  padding: 2px 4px;
  border-radius: 3px;
  font-family: "Courier New", Courier, monospace;
  display: inline-block;
  overflow-wrap: break-word;
  color: #000; /* Ensure code text is black */
}

code.block {
  display: block;
  margin-top: 5px;
}

strong {
  color: #ff4500;
}

/* Responsive design */
@media (min-width: 768px) {
  .about {
    padding: 40px;
  }

  h2 {
    font-size: 2.5rem;
  }

  h3 {
    font-size: 1.8rem;
  }
}
</style>
