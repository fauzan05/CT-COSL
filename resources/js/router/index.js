import {
    createRouter,
    createWebHistory
} from 'vue-router';
import DashboardHome from '../components/dashboard/DashboardHome.vue';
import LoginPage from '../components/auth/LoginPage.vue';
import ToolstringCoiledTubingCategory from '../components/dashboard/ToolstringCoiledTubingCategory.vue';
import Reporting from '../components/dashboard/Reporting.vue';

const routes = [{
    path: '/dashboard',
    component: DashboardHome,
},
{
    path: '/toolstring-coiled-tubing/:slug/:toolstringCategoryId',
    component: ToolstringCoiledTubingCategory,
},
{
    path: '/reporting/:slug',
    component: Reporting,
},
{
    path: '/login',
    component: LoginPage,
}
];

export default createRouter({
    history: createWebHistory('/ct-cosl/'),
    routes,
});
