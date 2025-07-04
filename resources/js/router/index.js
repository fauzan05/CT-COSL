import {
    createRouter,
    createWebHistory
} from 'vue-router';
import DashboardHome from '../components/dashboard/DashboardHome.vue';
// import UserList from '../components/users/UserList.vue';

const routes = [{
        path: '/dashboard',
        component: DashboardHome
    },
    //   { path: '/users', component: UserList },
];

export default createRouter({
    history: createWebHistory(),
    routes,
});
