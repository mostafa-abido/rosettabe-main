export const homesModule = {
  namespaced: true,

  state: () => ({
    homes: [],
    home: []
  }),

  mutations: {
    SET_HOMES(state, value) {
      state.homes = value;
    },

    SET_HOME(state, value) {
      state.home = value.data;
    },
  },
  actions: {
    async getHomes({ dispatch, commit }) {
      return axios
        .get('/api/homes')
        .then((response) => {
          commit('SET_HOMES', response.data);
        })
        .catch((e) => console.log(e));
    },

    async getHome({ dispatch, commit }, home) {
      return axios.get('/api/home/' + home).then((response) => {
        commit('SET_HOME', response.data);
      });
    },

  },
  getters: {
    homes(state) {
      return state.homes;
    },

    home(state) {
      return state.home;
    },
  },
};
