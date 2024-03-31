import { createApp } from "vue";
import App from "./App.vue";
import router from "./router/router.js";
import PrimeVue from "primevue/config";
import Button from "primevue/button";
import ToastService from "primevue/toastservice";

//import the required css for bootstrap ,  prime vue and prime icons
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap";
import "bootstrap/dist/css/bootstrap.css";
import "bootstrap-vue/dist/bootstrap-vue.css";
import "primevue/resources/themes/aura-light-green/theme.css";
import "primevue/resources/primevue.min.css";
import "primeicons/primeicons.css";

//creating the vue app
const app = createApp(App);

//initialize the app with prime vue with prime vue toast service and vue router
app.use(PrimeVue).use(ToastService);
app.use(router);

app.mount("#app");
