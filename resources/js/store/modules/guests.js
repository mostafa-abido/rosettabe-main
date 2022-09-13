export const guestsModule = {
  namespaced: true,

  state: () => ({
    guests: [],
    guest: [],
    next: null,
  }),

  mutations: {
    SET_GUESTS(state, value) {
      state.guests = value;
    },

    SET_GUEST(state, value) {
      state.guest = value.data;
    },

    LOAD_GUESTS(state, value) {
      state.next = value?.next_page_url;

      for (var i in value.data) {
        state.guests.push(value.data[i]);
      }
    },
  },
  actions: {
    async getGuests({ dispatch, commit }) {
      return axios
        .get('/api/guests')
        .then((response) => {
          commit('SET_GUESTS', response.data);
        })
        .catch((e) => console.log(e));
    },

    async getGuest({ dispatch, commit }, guest) {
      return axios.get('/api/guests/' + guest).then((response) => {
        commit('SET_GUEST', response.data);
      });
    },

    async loadMoreGuests({ dispatch, commit }, url) {
      return await axios.get(url).then((response) => {
        commit('LOAD_GUESTS', response.data);
      });
    },
  },
  getters: {
    guests(state) {
      return state.guests;
    },

    guest(state) {
      return state.guest;
    },

    nextPage(state) {
      return state.next;
    },
  },
};
