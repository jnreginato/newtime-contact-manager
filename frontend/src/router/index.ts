import {createRouter, createWebHistory, type RouteRecordRaw} from 'vue-router';
import ContactsTable from '@/components/ContactsTable.vue';

const NewContact = () => import('@/pages/NewContact.vue');
const EditContact = () => import('@/pages/EditContact.vue');

const routes: RouteRecordRaw[] = [
  {path: '/', redirect: '/contacts'},
  {path: '/contacts', component: ContactsTable, meta: {title: 'Contatti'}},
  { path: '/contacts/new', component: NewContact, meta: { title: 'Nuovo Contatto' } },
  { path: '/contacts/:id/edit', component: EditContact, meta: { title: 'Modifica Contatto' } }
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
