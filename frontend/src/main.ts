import {createApp} from 'vue';
import App from './App.vue';
import {router} from './router';
import {Toaster} from 'vue-sonner'
import {createPinia} from 'pinia';
import piniaPersist from 'pinia-plugin-persistedstate';
import './style.css';
import 'vue-sonner/style.css';

const pinia = createPinia();
pinia.use(piniaPersist);

const app = createApp(App);
app.use(router);
app.use(pinia);
app.component('Toaster', Toaster);
app.mount('#app');
