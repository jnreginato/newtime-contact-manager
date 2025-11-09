import { createApp } from 'vue';
import App from './App.vue';
import { router } from './router';
import { createPinia } from 'pinia';
import piniaPersist from 'pinia-plugin-persistedstate';
import './style.css';

const pinia = createPinia();
pinia.use(piniaPersist);

createApp(App)
  .use(router)
  .use(pinia)
  .mount('#app');
