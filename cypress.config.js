import { defineConfig } from "cypress";

export default defineConfig({
  e2e: {
    baseUrl: 'http://localhost:8000',
    viewportWidth: 1600,    // Set the width of the viewport
    viewportHeight: 800,
    video: true,                      // Enable video recording
    videoCompression: 1,             // Video compression level (lower value = better quality)
    videoFrameRate: 60,               // Increase frame rate (e.g., 60 fps)
    videosFolder: 'cypress/videos',
    // Increase default timeout to 10 seconds (10000 ms)
    defaultCommandTimeout: 10000,

    // Enable retries for failed tests
    retries: {
      runMode: 5,
      openMode: 5,
    },
    setupNodeEvents(on, config) {
      // implement node event listeners here
    },
  },
});
