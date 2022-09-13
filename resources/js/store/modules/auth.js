import axios from 'axios';
const getCSRF = async () => axios.get('/sanctum/csrf-cookie');
const getUser = async ({ commit }) => {
  return axios
    .get('/api/user')
    .then(({ data }) => {
      commit('SET_AUTHENTICATED', true);
      commit('SET_USER', data);

      localStorage.setItem('userData', JSON.stringify(data));
    })
    .catch((e) => {
      console.log('failed to get user', e);
      localStorage.clear();
      commit('SET_AUTHENTICATED', false);
      commit('SET_USER', null);
    });
};

export const authModule = {
  namespaced: true,

  state: () => ({
    authenticated: false,
    user: {},
    errors: [],
  }),
  mutations: {
    SET_AUTHENTICATED(state, value) {
      state.authenticated = value;
    },

    SET_USER(state, value) {
      state.user = value;
    },

    SET_ERRORS(state, value) {
      state.errors = value;
    },
  },
  actions: {
    getCSRF,
    async signIn({ commit, dispatch }, credentials) {
      await getCSRF();

      return await axios
        .post('/api/login', credentials)
        .then(async ({ data }) => {
          localStorage.setItem('access_token', data.access_token);
        })
        .catch((error) => {
          if (error?.response?.data?.errors) {
            commit('SET_ERRORS', error.response.data.errors);
          }
        });
    },

    async forgotPassword({ dispatch, commit }, email) {
      return await axios.post('/api/forgot/password', email).catch((error) => {
        commit('SET_ERRORS', error.response.data.errors);
      });
    },

    async restorePassword({ dispatch, commit }, email) {
      return await axios.post('/api/reset/password', email).catch((error) => {
        commit('SET_ERRORS', error.response.data.errors);
      });
    },

    async signOut({ dispatch }) {
      return await axios.post('/logout');
      localStorage.removeItem('access_token');
    },

    getUser,
  },
  getters: {
    authenticated(state) {
      return state.authenticated;
    },

    user(state) {
      if (!state.user?.id) {
        try {
          const cachedData = localStorage.getItem('userData');
          return JSON.parse(cachedData);
        } catch (e) {}
      }
      return state.user;
    },

    errors(state) {
      return state.errors;
    },
  },
};
