import { createRouter, createWebHistory } from 'vue-router';
import Clients from '../components/Clients.vue';
import Deals from '../components/Deals.vue';
import Tasks from '../components/Tasks.vue';
import Login from '../components/Login.vue';

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
  },
  {
    path: '/login',
    name: 'Login',
    component: Login
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token');
    const isAuthPage = to.path === '/login';

    if (!token && !isAuthPage) {
      next('/login');
    } else if (token && isAuthPage) {
      next('/clients');
    } else {
      next();
    }
  });

export default router;
