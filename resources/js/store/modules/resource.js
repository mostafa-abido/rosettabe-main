export const resourceModule = {
  namespaced: true,

  state: () => ({
    resources: [],
    resource: [],
    next: null,
  }),

  mutations: {
    SET_RESOURCES(state, value) {
      state.next = value.next_page_url;
      state.resources = value.data;
    },

    SET_RESOURCE(state, value) {
      state.resource = value.data;
    },

    LOAD_RESOURCES(state, value) {
      state.next = value.next_page_url;

      for (var i in value.data) {
        state.resources.push(value.data[i]);
      }
    },
  },
  actions: {
    async getResources({ dispatch, commit }) {
      axios.get('/api/resources').then((response) => {
        commit('SET_RESOURCES', response.data);
      });
    },

    async getResource({ dispatch, commit }, resource) {
      axios.get('/api/resources/' + resource).then((response) => {
        commit('SET_RESOURCE', response.data);
      });
    },
  },
  getters: {
    resources(state) {
      return state.resources;
    },

    resource(state) {
      return state.resource;
    },

    nextPage(state) {
      return state.next;
    },
  },
};
