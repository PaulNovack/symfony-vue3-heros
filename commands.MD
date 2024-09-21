### Create Vite App

```
npm create vite@latest heros-vue --template vue
cd heros-vue
npm install
npm run dev
npm install -D @tailwindcss/postcss tailwindcss postcss autoprefixer
npm install vue-router@4
# Install prettier and eslint
npm install --save-dev eslint prettier eslint-plugin-vue eslint-config-prettier eslint-plugin-prettier @typescript-eslint/eslint-plugin @typescript-eslint/parser
npx eslint --init
You can also run this command directly using 'npm init @eslint/config@latest'.

> heros-vue@0.0.0 npx
> create-config

@eslint/create-config: v1.3.1

✔ How would you like to use ESLint? · problems
✔ What type of modules does your project use? · esm
✔ Which framework does your project use? · vue
✔ Does your project use TypeScript? · typescript
✔ Where does your code run? · browser
The config that you've selected requires the following dependencies:

eslint, globals, @eslint/js, typescript-eslint, eslint-plugin-vue
✔ Would you like to install them now? · No / Yes
✔ Which package manager do you want to use? · npm
☕️Installing...

added 2 packages, changed 1 package, and audited 279 packages in 582ms

79 packages are looking for funding
  run `npm fund` for details

found 0 vulnerabilities
Successfully created /home/pnovack/2024-code-2/heros-vue/eslint.config.js file.

npm install cypress --save-dev
npm install @cypress/vite-dev-server --save-dev
npm install @cypress/vue --save-dev
```