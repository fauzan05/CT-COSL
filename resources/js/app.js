import "./bootstrap";
import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import { createPinia } from "pinia";
import HighchartsVue from 'highcharts-vue'

const app = createApp(App);
app.use(HighchartsVue)
app.use(createPinia());
app.use(router);
app.mount("#app");
