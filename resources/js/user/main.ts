import { createApp } from "vue";

import App from "@user/app/App.vue";
import router from "@user/common/router/router";
import store from "@user/common/store/store";
import i18n from "@user/common/locale/locale";

const app = createApp(App);

app.use(store);
app.use(router);
app.use(i18n);

app.mount("#app");
