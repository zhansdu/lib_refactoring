import { createApp } from "vue";

import App from "@user/app/App.vue";
import router from "@user/app/router/router.js";
import store from "@user/app/store/store.js";

const app = createApp(App);

app.use(store);
app.use(router);

app.mount("#app");
