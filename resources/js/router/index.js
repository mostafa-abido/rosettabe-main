import axios from 'axios';
import { createWebHistory, createRouter } from 'vue-router';
import routes from './routes';

const updateUnreads = async (to) => {
  try {
    await axios.post('/api/notification', { type: to.name });
    const { data } = await axios.get('/api/notification');

    if (data) {
      localStorage.setItem('unread', JSON.stringify(data));
    }
  } catch (e) {
    console.error(e);
  }
};

const router = createRouter({
  history: createWebHistory(),
  routes: routes,
});

router.beforeEach((to, from, next) => {
  const scrollableItem = document.getElementById('app')?.children[0];
  if (scrollableItem && scrollableItem.scrollTop > 0) {
    scrollableItem.scrollTop = 0;
  }
  if (
    to.matched.some((record) => record.meta.requiresAuth) &&
    !localStorage.getItem('access_token')
  ) {
    // this route requires auth, check if logged in
    // if not, redirect to login page.
    next({
      path: '/app/auth/login',
      query: { redirect: to.fullPath },
    });
  } else {
    next(); // make sure to always call next()!
  }
});

router.afterEach((to) => {
  if (
    to.matched.some((record) => !record.meta.requiresAuth) &&
    localStorage.getItem('access_token')
  ) {
    updateUnreads(to);
  }
});

export default router;
