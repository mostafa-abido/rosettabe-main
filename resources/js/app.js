/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');
import { createApp } from 'vue';
import linkify from 'vue-linkify';
import Notifications from 'notiwind';
import TextareaAutosize from './components/TextareaAutosize/TextareaAutosize';
import router from './router';
import store from './store';
import { authModule } from './store/modules/auth';
import axios from 'axios';
import registerServiceWorker from './registerServiceWorker';
import BaseView from './pages/layouts/BaseView';

axios.defaults.withCredentials = true;
axios.defaults.baseURL = process.env.MIX_API_URL;
axios.defaults.headers.common['Accept'] = 'application/json';

axios.interceptors.request.use(
  function (config) {
    // assume your access token is stored in local storage
    // (it should really be somewhere more secure but I digress for simplicity)
    let token = localStorage.getItem('access_token');
    if (token) {
      config.headers['Authorization'] = `Bearer ${token}`;
    }
    return config;
  },
  function (error) {
    // Do something with request error
    return Promise.reject(error);
  }
);

axios.interceptors.response.use(
  function (response) {
    if (['OK', 'No Content', 'Created'].includes(response.statusText)) {
      return response;
    }

    console.log('axios.interceptors.response: not OK', response);
  },
  (error) => {
    console.error(
      `Response Error [${error.response.status}]: ${error.response.statusText}`,
      error.response.data
    );

    const status = error?.response ? error.response.status : null;

    switch (status) {
      case 401:
        localStorage.removeItem('access_token');
        window.location.href = '/app';
        return null;
      case 403:
      case 419:
        return authModule.actions
          .getCSRF()
          .then(() => axios.request(error.config));
      default:
        return Promise.reject(error);
    }
  }
);

// Create and mount the root instance.
const app = createApp(BaseView);
// Make sure to _use_ the router instance to make the
// whole app router-aware.
app.use(router);
app.use(store);
app.use(Notifications);

app.component('TextareaAutosize', TextareaAutosize);

app.directive('linkified', linkify);

app.mount('#app');

registerServiceWorker();
