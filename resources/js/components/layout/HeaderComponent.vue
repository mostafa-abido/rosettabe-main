<template>
  <!-- When the mobile menu is open, add `overflow-hidden` to the `body` element to prevent double scrollbars -->
    <header class="top-0 z-10 w-full bg-white shadow-sm lg:overflow-y-visible">
      <div class="max-w-7xl mx-auto px-4 py-3 sm:px-6 lg:px-8">
        <div
          class="relative flex justify-center xl:grid xl:grid-cols-12 lg:gap-8"
        >
          <div
            class="
              flex
              lg:static
              xl:col-span-2
            "
          >
            <div class="flex-shrink-0 flex items-center">
              <router-link :to="{ name: 'root' }">
                <img class="block h-8 w-auto" src="/logo.png" alt="Workflow" />
              </router-link>
            </div>
          </div>
          <div class="hidden lg:flex min-w-0 flex-1 md:px-8 lg:px-0 xl:col-span-6"></div>
          <div
            class="flex absolute lg:block right-0 lg:items-center lg:justify-end xl:col-span-4"
          >
            <!-- Profile dropdown -->
            <Menu as="div" class="flex-shrink-0 relative ml-5">
              <div>
                <MenuButton
                  class="
                    bg-white
                    rounded-full
                    flex
                    focus:outline-none
                    focus:ring-rose-500 focus:ring-rose-500 focus:ring-rose-500
                  "
                >
                  <span class="sr-only">Open user menu</span>
                  <user-avatar
                    size="8"
                    :src="user?.avatar || user?.data?.avatar"
                  />
                </MenuButton>
              </div>
              <transition
                enter-active-class="transition ease-out duration-100"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95"
              >
                <MenuItems
                  class="
                    origin-top-right
                    absolute
                    z-20
                    right-0
                    mt-2
                    w-48
                    rounded-md
                    shadow-lg
                    bg-white
                    ring-1 ring-black ring-opacity-5
                    py-1
                    focus:outline-none
                  "
                >
                  <form action="#" method="POST" @submit.prevent="logout">
                    <MenuItem as="a"
                        @click="openProfile"
                        :class="[
                          'block py-2 px-4 cursor-pointer text-sm text-gray-700',
                        ]">My Profile
                    </MenuItem>
                    <MenuItem as="a"
                        @click="openNotifications"
                        :class="[
                          'block py-2 px-4 cursor-pointer text-sm text-gray-700',
                        ]">My Notifications
                    </MenuItem>
                    <MenuItem as="a"
                        href="/app/policy"
                        target="_blank"
                        v-if="isNativeApp()"
                        :class="[
                          'block py-2 px-4 cursor-pointer text-sm text-gray-700',
                        ]">
                        App Policy
                    </MenuItem>
                    <MenuItem as="a"
                        @click="logout"
                        :class="[
                          'block py-2 px-4 cursor-pointer hover:bg-gray-100 focus:bg-gray-100 text-sm text-gray-700',
                        ]"
                        >Logout
                    </MenuItem>
                  </form>
                </MenuItems>
              </transition>
            </Menu>
          </div>
        </div>
      </div>
    </header>
</template>

<script>
import {
  Menu,
  MenuButton,
  MenuItem,
  MenuItems,
  Popover,
  PopoverButton,
  PopoverPanel,
} from '@headlessui/vue';
import * as Pages from '../../constants/pages';
import { SearchIcon } from '@heroicons/vue/solid';
import { MenuIcon, XIcon } from '@heroicons/vue/outline';
import UserAvatar from '../avatar/UserAvatar';
import { mapGetters, mapActions } from 'vuex';

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
  { name: Pages.FEED, to: { name: 'feed' }, seen: unread.feed },
  { name: Pages.CHATS, to: { name: 'chats' }, seen: unread.chat },
  { name: Pages.USERS, to: { name: 'users' }, seen: unread.colleagues },
  { name: Pages.RESOURCES, to: { name: 'resources' }, seen: unread.resources },
];

export default {
  components: {
    UserAvatar,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    Popover,
    PopoverButton,
    PopoverPanel,
    MenuIcon,
    SearchIcon,
    XIcon,
  },
  computed: {
    ...mapGetters({
      user: 'auth/user',
    }),
    navigation() {
      return this?.user?.data?.is == 2
        ? [{ name: Pages.CHATS, to: { name: 'chats' } }]
        : navigation;
    },
  },

  methods: {
    ...mapActions({
      logout: 'auth/signOut',
    }),
    isNativeApp() {
      return !!window.isNativeApp
    },
    openProfile() {
      if (this?.user?.data.id) {
        this.$router.push(`/app/users/${this?.user?.data?.id}`)
      }
    },
    openNotifications() {
      if (this?.user?.data.id) {
        this.$router.push(`/app/notifications`)
      }
    },
    async logout() {
      console.log('logout');

      localStorage.removeItem('access_token');
      localStorage.removeItem('userData');

      await this.$router.replace({
        path: '/app/auth/login'
      });
    },
  },
};
</script>
