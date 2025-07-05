import './bootstrap';
import {createApp} from 'vue';
import router from './router';

import Auth from './Auth.vue';

createApp(Auth).use(router).mount('#auth');
