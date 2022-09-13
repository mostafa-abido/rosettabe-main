<template>
  <div class="w-full">
    <div class="flex flex-col pb-0 chat-view justify-end min-h-20 bg-white">
        <div class="sticky top-0 bg-gray-100 z-10">
          <flex-title
            v-if="!isUser && chat"
            :title="chat?.name"
            :backTo="{ name: 'chats' }"
          />
          <flex-title v-else :title="chat?.name || ''" />
        </div>
        <transition>
          <template v-if="chat?.id?.toString() === $route.params.chatId">
            <list-messages-component
              v-if="user.data && chat?.messages?.length > 0"
              class="bg-white flex-grow"
              :messages="messagesOrdered"
            />
            <div
              class="flex flex-1 flex-col items-center justify-center text-center"
              v-else
            >
              <p class="text-regular text-gray-300">No messages</p>
              <p class="text-sm text-gray-300">
                Be the first to start writing here.
              </p>
            </div>
          </template>
          <div
            v-else
            class="chat-start"
            style="height: 100%; min-height: inherit"
          />
        </transition>

      <div class="bg-white fixed left-0 bottom-0 w-full lg:sticky">
        <send-message-component :chat="chat?.id?.toString()" />
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import FlexTitle from '../../components/layout/menus/FlexTitleComponent';
import ListMessagesComponent from '../../components/chats/ListMessagesComponent';
import SendMessageComponent from '../../components/chats/SendMessageComponent';

export default {
  name: 'ChatShowView',
  components: { FlexTitle, SendMessageComponent, ListMessagesComponent },

  data() {
    return {
      listener: null,
      scrolled: false,
      upload: false,
    };
  },

  computed: {
    ...mapGetters({
      user: 'auth/user',
    }),
    chat: {
      get() {
        return this.$store.getters['chats/chat'](this.$route.params.chatId);
      },
    },
    messagesOrdered: {
      get() {
        if (this?.chat?.messages) {
          return [...this.chat.messages].reverse();
        } else {
          return null;
        }
      },
    },
    isUser() {
      return this?.user?.data?.is == 2;
    },
  },

  methods: {
    onScroll(e) {
      if (
        e.target.scrollTop > 10 &&
        e.target.scrollTop < 300 &&
        this.scrolled
      ) {
        this.getMessages({ id: this.chat.id, cursor: this.chat.nextCursor });
      }
    },
    scrollBottom() {
      const scrollableView = document.getElementById('main-view');
      if (scrollableView) {
        scrollableView.scrollTo({
          top: scrollableView.scrollHeight - scrollableView.clientHeight,
          left: 0,
          behavior: 'auto',
        });
        this.scrolled = true;
      }
    },
    loadData(cursor) {
      if (this.$route.params.chatId) {
        this.getChat(this.$route.params.chatId);
        this.getMessages({ id: this.$route.params.chatId, cursor });
      }
    },
    listenOnScrollChange() {
      if (!document.getElementById('main-view')) return;
      this.listener = document.getElementById('main-view').addEventListener(
        'scroll',
        (e) => {
          this.onScroll(e);
        },
        {
          capture: false,
          passive: true,
        }
      );
    },
    ...mapActions({
      getMessages: 'chats/getMessages',
      getChat: 'chats/getChat',
      setMessageRead: 'chats/setMessageRead',
    }),
  },
  watch: {
    messagesOrdered(val, oldVal) {
      if (
        val &&
        oldVal &&
        val.length > oldVal.length &&
        val[val.length - 1] !== oldVal[oldVal.length - 1]
      ) {
        setTimeout(() => {
          this.$nextTick(() => {
            this.scrollBottom();
            this.listenOnScrollChange();
          });
        }, 100);
      }
    },
    chat(val, oldVal) {
      if (!val.messages) {
        return;
      }
      if (val.messages?.length > 0 && this.scrolled) {
        if (this?.user?.data && val.members) {
          const chatMemberMe = val.members.find(
            ({ id }) => id === this.user.data.id
          );
          if (chatMemberMe && chatMemberMe.unreads.length) {
            clearTimeout(this.timeout);
            this.timeout = setTimeout(async () => {
              const ids = chatMemberMe.unreads
                .filter(({ chat_id }) => chat_id === this.chat.id)
                .map(({ id }) => id);

              await this.setMessageRead(ids);
              await this.loadData();
            }, 10000);
          }
        }
      }

      if (
        val.messages.length > 0 &&
        !this.scrolled &&
        val.id?.toString() === this.$route.params.chatId
      ) {
        setTimeout(() => {
          this.$nextTick(() => {
            this.scrollBottom();
            this.listenOnScrollChange();
          });
        }, 100);
      }
    },
  },

  created() {
    this.loadData();
  },
  mounted() {},
  unmounted() {
    if (this.listener) {
      document.removeEventListener(this.listener);
    }
  },
};
</script>

<style scoped>
.chat-view {
  min-height: calc(100vh - 56px - 70px);
}
</style>
