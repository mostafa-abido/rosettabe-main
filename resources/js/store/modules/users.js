export const usersModule = {
  namespaced: true,

  state: () => ({
    users: [],
    user: [],
    next: null,
  }),

  mutations: {
    SET_USERS(state, value) {
      state.users = value;
    },

    SET_USER(state, value) {
      state.user = value.data;
    },

    LOAD_USERS(state, value) {
      state.next = value?.next_page_url;

      for (var i in value.data) {
        state.users.push(value.data[i]);
      }
    }
  },
  actions: {
    async getUsers({ dispatch, commit }) {
      return axios
        .get('/api/colleagues')
        .then((response) => {
          commit('SET_USERS', response.data);
        })
        .catch((e) => console.log(e));
    },

    async getUser({ dispatch, commit }, user) {
      return axios.get('/api/users/' + user).then((response) => {
        commit('SET_USER', response.data);
      });
    },

    async loadMore({ dispatch, commit }, url) {
      return axios.get(url).then((response) => {
        commit('LOAD_USERS', response.data);
      });
    },

  },
  getters: {
    users(state) {
      return state.users;
    },

    user(state) {
      return state.user;
    },

    nextPage(state) {
      return state.next;
    },
  },
};
