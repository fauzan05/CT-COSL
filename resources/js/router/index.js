import {
    createRouter,
    createWebHistory
} from 'vue-router';
import DashboardHome from '../components/dashboard/DashboardHome.vue';
import LoginPage from '../components/auth/LoginPage.vue';
import ToolstringCoiledTubing from '../components/dashboard/ToolstringCoiledTubing.vue';
// import UserList from '../components/users/UserList.vue';

const routes = [
    {
        path: '/dashboard',
        component: DashboardHome
    },
    {
        path: '/toolstring-coiled-tubing',
        component: ToolstringCoiledTubing
    },
    {
        path: '/login',
        component: LoginPage
    }
];

export default createRouter({
    history: createWebHistory('/ct-cosl/public/'),
    routes,
});
