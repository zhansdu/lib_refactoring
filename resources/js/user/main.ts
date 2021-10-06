import { createApp } from "vue";

import App from "@user/app/App.vue";
import router from "@user/app/router/router.js";
import store from "@user/app/store/store.js";
import i18n from "@user/app/locale/locale";
import { useI18n } from "vue-i18n";

const app = createApp(App);

app.use(store);
app.use(router);
app.use(i18n);
app.use(useI18n);

app.mount("#app");
