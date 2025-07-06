import "./bootstrap";
import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import { createPinia } from "pinia";
import HighchartsVue from "highcharts-vue";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
const toastOptions = {
    position: "top-right",
    timeout: 3000,
    closeOnClick: true,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    hideProgressBar: false,
    closeButton: "button",
    icon: true,
    rtl: false,
    transition: "Vue-Toastification__bounce",
    maxToasts: 20,
    newestOnTop: true,
};
const app = createApp(App);
app.use(Toast, toastOptions);
app.use(HighchartsVue);
app.use(createPinia());
app.use(router);
app.mount("#app");
