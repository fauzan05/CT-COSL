import {
    createRouter,
    createWebHistory
} from 'vue-router';
import DashboardHome from '../components/dashboard/DashboardHome.vue';
import LoginPage from '../components/auth/LoginPage.vue';
// import UserList from '../components/users/UserList.vue';

const routes = [
    {
        path: '/dashboard',
        component: DashboardHome
    },
    {
        path: '/login',
        component: LoginPage
    }
    //   { path: '/users', component: UserList },
];

export default createRouter({
    history: createWebHistory('/ct-cosl/public/'),
    routes,
});
