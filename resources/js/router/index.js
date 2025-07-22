import { createRouter, createWebHistory } from "vue-router";
import DashboardHome from "../components/dashboard/DashboardHome.vue";
import LoginPage from "../components/auth/LoginPage.vue";
import ToolstringCoiledTubingType from "../components/dashboard/ToolstringCoiledTubingType.vue";
import Reporting from "../components/dashboard/Reporting.vue";
import Thread from "../components/dashboard/Thread.vue";
import Wellstack from "../components/dashboard/WellstackType.vue";
import Users from "../components/dashboard/Users.vue";
import JobTracker from "../components/dashboard/JobTracker.vue";

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
        path: "/reporting",
        component: Reporting,
    },
    {
        path: "/login",
        component: LoginPage,
    },
    {
        path: "/wellstack/:slug/:wellstackTypeId",
        component: Wellstack,
    },
    {
        path: "/users",
        component: Users,
    },
    {
        path: "/job-tracker",
        component: JobTracker,
    }
];

export default createRouter({
    history: createWebHistory(import.meta.env.VITE_APP_ENV === 'production' ? '/' : '/ct-cosl/public/'),
    routes,
});
