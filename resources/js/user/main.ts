import { createSSRApp } from "vue";

import App from "@user/app/App.vue";
import router from "@/js/user/common/router/router";
import store from "@/js/user/common/store/store";
import i18n from "@user/common/locale/locale";

const app = createSSRApp(App);

app.use(store);
app.use(router);
app.use(i18n);

app.mount("#app");
