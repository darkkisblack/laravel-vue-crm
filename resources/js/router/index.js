import { createRouter, createWebHistory } from 'vue-router';
import Clients from '../components/Clients.vue';
import Deals from '../components/Deals.vue';
import Tasks from '../components/Tasks.vue';

const routes = [
  {
    path: '/',
    redirect: '/clients',
  },
  {
    path: '/clients',
    name: 'Clients',
    component: Clients
  },
  {
    path: '/deals',
    name: 'Deals',
    component: Deals
  },
  {
    path: '/tasks',
    name: 'Tasks',
    component: Tasks
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
