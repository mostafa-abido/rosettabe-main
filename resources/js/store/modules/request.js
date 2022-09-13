export const requestModule = {
  namespaced: true,

  state: () => ({
    request: [],
  }),
  mutations: {},
  actions: {
    async storeRequest({ commit }, data) {
      await axios.post('/api/requests', data);
    },
  },
  getters: {},
};
