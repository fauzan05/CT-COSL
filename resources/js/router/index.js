import { createRouter, createWebHistory } from "vue-router";
import DashboardHome from "../components/dashboard/DashboardHome.vue";
import LoginPage from "../components/auth/LoginPage.vue";
import ToolstringCoiledTubingType from "../components/dashboard/ToolstringCoiledTubingType.vue";
import Reporting from "../components/dashboard/Reporting.vue";
import Thread from "../components/dashboard/Thread.vue";

const routes = [
    {
        path: "/dashboard",
        component: DashboardHome,
    },
    {
        path: "/thread",
        component: Thread,
    },
    {
        path: "/toolstring-coiled-tubing/:slug/:toolstringTypeId",
        component: ToolstringCoiledTubingType,
    },
    {
        path: "/reporting/:slug",
        component: Reporting,
    },
    {
        path: "/login",
        component: LoginPage,
    },
];

export default createRouter({
    history: createWebHistory("/ct-cosl/"),
    routes,
});
