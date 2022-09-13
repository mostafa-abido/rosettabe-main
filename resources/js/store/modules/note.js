export const noteModule = {
  namespaced: true,

  state: () => ({
    note: [],
  }),
  mutations: {},
  actions: {
    async storeNote({ commit }, data) {
      await axios.post('/api/notes', data);
    },
    async editNote({ commit }, { id, data }) {
      await axios.put(`/api/notes/${id}`, data);
    },

    async deleteNote({ commit }, id) {
      console.log(id, 'delete not implemented');
      await axios.delete(`/api/notes/${id}`);
    },
  },
  getters: {},
};
