<!-- This example requires Tailwind CSS v2.0+ -->
<template>
  <nav class="sticky top-12 space-y-4 mt-12" aria-label="Sidebar">
    <router-link
      v-bind:key="link.name"
      v-for="link in navigation"
      v-show="user?.data?.is !== 2 || false"
      :to="{ name: link.name }"
      :class="[
        $route.name === link.name
          ? 'bg-gray-100 text-gray-900'
          : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
        'flex items-center px-3 py-2 text-sm font-medium rounded-md',
      ]"
    >
      <span class="truncate">
        {{ link.title }}
      </span>
      <div
        v-show="link.seen"
        class="ml-1 bg-blue-500 flex-shrink-0 w-1.5 h-1.5 rounded-full"
        aria-hidden="true"
      />
    </router-link>
  </nav>
</template>

<script>
import { mapState } from 'vuex';
import * as Pages from '../../constants/pages';

const getNavigationWithStatus = () => {
  let unreadString = localStorage.getItem('unread');
  let unread = {};

  if (unreadString) {
    try {
      unread = JSON.parse(unreadString);
    } catch (e) {
      console.error(e);
    }
  }

  const navigation = [
    {
      name: 'feed',
      title: Pages.FEED,
      seen: unread.feed,
    },
    {
      name: 'chats',
      title: Pages.CHATS,
      seen: unread.chat,
    },
    {
      name: 'users',
      title: Pages.USERS,
      seen: unread.colleagues,
    },
    {
      name: 'resources',
      title: Pages.RESOURCES,
      seen: unread.resources,
    },
    {
      name: 'guests',
      title: Pages.GUESTS,
      seen: unread.colleagues,
    },

  ];

  return navigation;
};

export default {
  data() {
    return {
      navigation: getNavigationWithStatus(),
    };
  },
  computed: {
    ...mapState('auth', ['user']),
  },
  created() {
    this.$watch(
      () => this.$route.params,
      (toParams, previousParams) => {
        this.navigation = getNavigationWithStatus();
      }
    );
  },
};
</script>
