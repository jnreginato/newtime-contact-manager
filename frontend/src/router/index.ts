import {createRouter, createWebHistory, type RouteRecordRaw} from 'vue-router';
import ContactsTable from '@/components/ContactsTable.vue';

const routes: RouteRecordRaw[] = [
  {path: '/', redirect: '/contacts'},
  {path: '/contacts', component: ContactsTable, meta: {title: 'Contatti'}},
];

export const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.afterEach((to) => {
  if (to.meta?.title) {
    document.title = String(to.meta.title);
  }
});
