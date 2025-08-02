import { createRouter, createWebHistory } from "vue-router";
import DashboardHome from "../components/dashboard/DashboardHome.vue";
import LoginPage from "../components/auth/LoginPage.vue";
import ToolstringCoiledTubingType from "../components/dashboard/ToolstringCoiledTubingType.vue";
import Reporting from "../components/dashboard/Reporting.vue";
import Thread from "../components/dashboard/Thread.vue";
import Wellstack from "../components/dashboard/WellstackType.vue";
import Users from "../components/dashboard/Users.vue";
import JobTracker from "../components/dashboard/JobTracker.vue";
import JobTrackerForm from "../components/dashboard/JobTracker/JobTrackerForm.vue";
import Nitrogen from "../components/dashboard/Nitrogen.vue";
import CoiledTubing from "../components/dashboard/CoiledTubing.vue";
import Profile from "../components/dashboard/Profile.vue";

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
        children: [
            {
                path: "create",
                component: JobTrackerForm,
                name: 'create-job-tracker'
            },
            {
                path: "edit/:id",
                component: JobTrackerForm,
                name: 'edit-job-tracker'
            }
        ]
    },
    {
        path: "/nitrogen",
        component: Nitrogen,
    },
    {
        path: "/coiled-tubing",
        component: CoiledTubing,
    },
    {
        path: "/profile",
        component: Profile,
    }
];

export default createRouter({
    history: createWebHistory(import.meta.env.VITE_APP_ENV === 'production' ? '/' : '/ct-cosl/public/'),
    routes,
});
