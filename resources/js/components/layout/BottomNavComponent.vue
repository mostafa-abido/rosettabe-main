<template>
  <div v-show="$route.name !== 'chat'" class="lg-hidden lg:hidden inline-flex fixed bottom-0 px-6 w-full justify-between bg-white border-t-2" aria-label="Bottom Navigation">
    <button
      type="button"
      v-bind:key="link.name"
      v-for="link in navigation"
      @click="onClick(link)"
      :class="[
        $route.name === link.name
        ? 'text-black font-bold'
        : 'text-gray-600',
        'px-2 py-3 text-xs font-medium'
      ]"
    >
      <span class="flex flex-col items-center lowercase">
        <component :is="link.icon" :class="['h-5 w-6', $route.name === link.name ? 'text-rose-600' : '']"></component>
      </span>
    </button>
  </div>
</template>

<script>
import {
  HomeIcon,
  UserGroupIcon,
  ChatAlt2Icon,
  FolderOpenIcon
} from '@heroicons/vue/outline';
import * as Pages from '../../constants/pages';

const navigation = [
  {
    name: 'feed',
    title: Pages.FEED,
    icon: HomeIcon,
  },
  {
    name: 'users',
    title: Pages.USERS,
    icon: UserGroupIcon,
  },
  {
    name: 'chats',
    title: Pages.CHATS,
    icon: ChatAlt2Icon,
  },
  {
    name: 'resources',
    title: Pages.RESOURCES,
    icon: FolderOpenIcon,
  },
];

export default {
  components: {
    HomeIcon,
    UserGroupIcon,
    ChatAlt2Icon,
    FolderOpenIcon
  },
  methods: {
    onClick(link) {
      document.getElementById('app').children[0].scrollTop = 0;
      this.$router.replace({ name: link.name })
    }
  },
  data() {
    return {
      navigation
    };
  }
};
</script>
