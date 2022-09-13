export const postsModule = {
  namespaced: true,

  state: () => ({
    posts: [],
    post: [],
    comments: [],
    next: null,
  }),
  mutations: {
    SET_POSTS(state, value) {
      state.next = value.links.next;
      state.posts = value.data;
    },

    SET_POST(state, value) {
      const postIndex = state.posts.findIndex(
        ({ id }) => id === value?.data?.id
      );

      if (postIndex >= 0) {
        state.posts[postIndex] = value.data;
      }

      state.post = value.data;
    },

    LOAD_POSTS(state, value) {
      state.next = value.links.next;

      for (var i in value.data) {
        state.posts.push(value.data[i]);
      }
    },

    ADD_COMMENT(state, value) {
      state.post.comments.push(value);
    },
  },
  actions: {
    async getPosts({ dispatch, commit }) {
      return axios.get('/api/posts').then((response) => {
        let posts = response.data.data;

        posts = posts.sort((first, second) => {
          if (first.required && !first.isReacted) {
            return -1;
          } else if (second.required && !second.isReacted) {
            return 1;
          }
          return 0;
        });

        commit('SET_POSTS', { ...response.data, data: posts });
      });
    },

    async getPost({ dispatch, commit }, post) {
      return axios.get('/api/posts/' + post).then((response) => {
        commit('SET_POST', response.data);
      });
    },

    async likePost({ dispatch, commit }, post) {
      return axios.post('/api/posts/' + post + '/like');
    },

    async completePost({ dispatch, commit }, post) {
      return axios.post('/api/posts/' + post + '/complete');
    },

    async storePost({ commit }, data) {
      return axios.post('/api/posts', data);
    },

    async storeComment({ commit }, data) {
      return axios.post('/api/comments', data);
    },

    async deleteComment({ commit }, { commentId }) {
      return axios.delete(`/api/comments/${commentId}`);
    },

    async loadMore({ dispatch, commit }, url) {
      return axios.get(url).then((response) => {
        commit('LOAD_POSTS', response.data);
      });
    },
  },
  getters: {
    posts(state) {
      return state.posts;
    },

    post(state) {
      return state.post;
    },

    comments(state) {
      return state.comments;
    },

    nextPage(state) {
      return state.next;
    },
  },
};
