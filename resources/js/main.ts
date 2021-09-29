import { createApp } from "vue";
import { createStore } from "vuex";
import { createRouter, createWebHistory } from "vue-router";

import App from "./App.vue";

const store = createStore({});
const router = createRouter({
    history: createWebHistory(),
    routes: [],
});

const app = createApp(App);

app.use(store);
app.use(router);

app.mount("#app");
