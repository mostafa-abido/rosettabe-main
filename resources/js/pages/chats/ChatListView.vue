<template>
  <div class="w-full">
    <flex-title :title="title" justify="center" :noBack="true"/>
    <div
      v-if="!loading && Object.keys(chats).length > 0"
      class="overflow-hidden pb-10"
    >
      <ul class="divide-y divide-gray-200 bg-white shadow rounded-lg">
        <li v-for="[key, chat] in Object.entries(this.chats)" :key="key">
          <router-link
            href="#"
            :to="{ name: 'chat', params: { chatId: key } }"
            class="block hover:bg-gray-50"
          >
            <div class="px-4 py-4 flex items-center sm:px-6">
              <div
                class="min-w-0 flex-1 sm:flex sm:items-center sm:justify-between align-center justify-between flex"
              >
                <div class="flex text-base items-center">
                  <p class="font-medium text-black-600 pr-2">
                    {{ chat.name }}
                  </p>
                  <div
                    v-show="hasUnread(chat)"
                    class="bg-blue-500 flex-shrink-0 w-2.5 h-2.5 rounded-full"
                    aria-hidden="true"
                  />
                </div>
                <div v-if="chat.team_only" class="flex items-center flex-shrink-0 sm:mt-0 sm:ml-5">
                  <div class="overflow-hidden -space-x-1">
                    <div class="flex align-center">
                      <span class="text-xs text-gray-400">team chat</span>
                    </div>
                    <!-- <img
                      v-for="member in chat.members.slice(0,10)"
                      :key="member.name"
                      class="inline-block h-6 w-6 rounded-full ring-2 ring-white"
                      :src="member.avatar"
                      :alt="member.avatar"
                    /> -->
                  </div>
                </div>
              </div>
              <div class="ml-2 flex-shrink-0">
                <ChevronRightIcon
                  class="h-5 w-5 text-gray-400"
                  aria-hidden="true"
                />
              </div>
            </div>
          </router-link>
        </li>
      </ul>
    </div>
    <div
      v-else-if="!loading"
      class="flex justify-center items-center align-center h-20 p-4 m-2"
    >
      <h3 class="text-bold text-gray-600 text-center">
        There's nothing shared with you.<br />
        <p class="leading-8">Please contact the English Rose HR Team.</p>
      </h3>
    </div>
    <template v-else>
      <div class="my-8">
        <div class="text-center text-xs text-gray-400">
          <p>Loading...</p>
        </div>
      </div>
    </template>
  </div>
</template>

<script>
import { CHATS } from '../../constants/pages';
import FlexTitle from '../../components/layout/menus/FlexTitleComponent';
import { CalendarIcon, ChevronRightIcon } from '@heroicons/vue/solid';
import { mapGetters, mapActions } from 'vuex';

export default {
  components: {
    FlexTitle,
    CalendarIcon,
    ChevronRightIcon,
  },
  data() {
    return { loading: true, title: CHATS };
  },

  computed: {
    ...mapGetters({
      chats: 'chats/chats',
      user: 'auth/user',
    }),
  },

  methods: {
    hasUnread({ id: chatId, members }) {
      if (this?.user?.data) {
        const chatMemberMe = members.find(({ id }) => id === this.user.data.id);
        if (chatMemberMe) {
          return (
            chatMemberMe.unreads.length > 0 &&
            chatMemberMe.unreads.some(({ chat_id }) => chat_id === chatId)
          );
        }
      }
      return false;
    },
    setLoading(val) {
      this.loading = val;
    },
    ...mapActions({
      getChats: 'chats/getChats',
    }),
  },

  created() {
    if (!Object.keys(this.chats).length) {
      this.setLoading(true);
    } else {
      this.setLoading(false);
    }

    this.getChats().then(() => {
      this.setLoading(false);
    });
  },
};
</script>
