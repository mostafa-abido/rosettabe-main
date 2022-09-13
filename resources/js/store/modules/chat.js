export const chatModule = {
  namespaced: true,

  state: () => ({
    chats: {},
  }),
  mutations: {
    SET_LOADING_MESSAGES(state, id) {
      if (!state.chats[id]) {
        state.chats[id] = {};
      }

      state.chats[id].loading = true;
    },
    SET_CHATS(state, value) {
      state.chats = value.reduce((acc, item) => {
        if (!acc[item.id]) {
          acc[item.id] = item;
        }
        return acc;
      }, {});

      localStorage.setItem('cache:chatListData', JSON.stringify(state.chats));
    },

    SET_CHAT(state, newChat) {
      if (!state.chats[newChat.id]) {
        state.chats[newChat.id] = {};
      }

      state.chats[newChat.id] = {
        ...newChat,
        messages: state?.chats?.[newChat.id]?.messages ?? null,
      };
    },

    SET_MESSAGES(state, data) {
      const id = data.id;

      console.log('chats/messages serverData');

      if (data.currentCursor === null && data.messages) {
        state.chats[id].messages = [...data.messages];
      } else if (state.chats[id]) {
        state.chats[id].messages = [
          ...(state.chats[id]?.messages || []),
          ...data.messages,
        ];
        state.chats[id].currentCursor = data.currentCursor;
        state.chats[id].nextCursor = data.nextCursor;
        state.chats[id].loading = false;
      }

      if (!data.currentCursor) {
        localStorage.setItem(
          'cache:chatData-' + id,
          JSON.stringify(state.chats[id])
        );
      }
    },

    ADD_MESSAGE(
      state,
      { chat, attachments, id, message, created_at, status, updated_at, user }
    ) {
      state.chats[chat.id].messages.unshift({
        attachments,
        created_at,
        id,
        message,
        unreadBy: [],
        updated_at,
        user,
      });
    },
  },
  actions: {
    async getMessages({ state, commit }, { id: chat_id, cursor = null }) {
      if (!chat_id) {
        return Promise.resolve();
      }

      const chat = state?.chats?.[chat_id];

      if ((chat?.loading ?? false) === false) {
        console.log(state.chats[chat_id]);
        console.log('currentCursor', chat?.currentCursor);
        console.log('nextCursor', chat?.nextCursor, cursor);
        console.log('messages', chat?.messages?.length);

        if (
          (chat?.currentCursor !== null && cursor !== null) ||
          (chat?.currentCursor && !cursor) ||
          chat?.currentCursor === cursor
        ) {
          return Promise.resolve();
        }

        const chatMessages = chat?.messages || [];
        const chatLength = chatMessages.length ?? 0;
        const lastMessage =
          chatLength > 0 ? chatMessages?.[chatLength - 1] : null;
        if (lastMessage) {
          if (chatLength >= 300) {
            return Promise.resolve();
          }
        }

        commit('SET_LOADING_MESSAGES', chat_id);

        return axios
          .get(`/api/messages/${chat_id}${cursor ? `?cursor=${cursor}` : ''}`)
          .then((response) => {
            commit('SET_MESSAGES', {
              id: chat_id,
              messages: response.data.data,
              currentCursor: cursor ?? null,
              nextCursor: response.data.links.next
                ? response.data.links.next.split('cursor=')[1]
                : null,
            });
          });
      }
    },
    async sendMessage({ commit }, message) {
      return axios.post('/api/messages/', message).then((response) => {
        commit('ADD_MESSAGE', response.data.data);
      });
    },
    async setMessageRead({ commit }, ids) {
      return axios.post(`/api/set-message-seen`, { ids });
    },
    async getChats({ commit }) {
      return axios.get('/api/chats').then((response) => {
        commit('SET_CHATS', response.data.data);
      });
    },
    async getChat({ commit }, chat) {
      return axios.get('/api/chats/' + chat).then((response) => {
        commit('SET_CHAT', response.data.data);
      });
    },
  },
  getters: {
    chats: (state) => {
      if (Object.keys(state.chats).length > 0) {
        return state.chats;
      } else {
        const cacheData = localStorage.getItem('cache:chatListData');

        if (cacheData) {
          console.log('chats/cacheData');
          try {
            return JSON.parse(cacheData);
          } catch (e) {}
        }

        return {};
      }
    },
    chat: (state) => (id) => {
      if (state.chats[id]?.messages) {
        return state.chats[id];
      } else {
        const cacheData = localStorage.getItem('cache:chatData-' + id);

        if (cacheData) {
          console.log('chat/cacheData', cacheData);
          try {
            const parsedCacheData = JSON.parse(cacheData);
            return {
              ...parsedCacheData,
              ...state.chats[id],
              messages: parsedCacheData.messages,
            };
          } catch (e) {}
        }

        return {};
      }
    },
  },
};
