<template>
  <div class="w-full">
    <flex-title :title="title" justify="center" :noBack="true"/>

    <div
      class="
        py-2
        lg:py-4
        bg-gray-100
        sticky
        top-0
        border-b
        border-gray-200
      "
    >
      <div class="w-full">
        <label for="search" class="sr-only">Search</label>
        <div class="relative text-sky-100 focus-within:text-gray-400">
          <div
            class="
              pointer-events-none
              absolute
              inset-y-0
              left-0
              pl-3
              flex
              items-center
            "
          >
            <SearchIcon class="flex-shrink-0 h-5 w-5" aria-hidden="true" />
          </div>
          <input
            id="search"
            name="search"
            class="
              block
              w-full
              bg-sky-700 bg-opacity-50
              py-2
              pl-10
              pr-3
              border border-transparent
              rounded-md
              shadow-sm
              leading-5
              placeholder-sky-100
              focus:outline-none
              focus:bg-white
              focus:ring-white
              focus:border-white
              focus:placeholder-gray-500
              focus:text-gray-900
              sm:text-sm
            "
            placeholder="Search"
            v-model="searchTerm"
            type="search"
          />
        </div>
      </div>
    </div>

    <ul v-if="users" class="grid grid-cols-2 gap-4 pt-4 pb-10">
      <li v-for="user in filteredUsers" :key="user.id">
        <router-link :to="{name:'user',params:{id:user.id}}">
          <div class="bg-white rounded-md border-2 h-40">
          <div class="flex flex-col justify-center align-center">
            <div class="flex justify-center align-center py-4">
              <img :src="user.avatar" class="bg-gray-600 rounded-full w-20 h-20 border-2 overflow-hidden max-w-full" />
            </div>
            <span class="text-center">{{user.name}} {{user.surname}}</span>
          </div>
          </div>
        </router-link>
      </li>
    </ul>
    <template v-else-if="filteredUsers?.length == 0 && searchTerm.length > 2">
      <div class="my-8">
        <div class="text-center text-xs text-gray-400">
          <p>No results</p>
        </div>
      </div>
    </template>
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
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue';
import { mapGetters, mapState, mapActions } from 'vuex';
import filter from 'lodash/filter';
import { XIcon } from '@heroicons/vue/outline';
import {
  ChevronDownIcon,
  ChevronUpIcon,
  SearchIcon,
} from '@heroicons/vue/solid';
import some from 'lodash/some';
import UserAvatar from '../../components/avatar/UserAvatar';
import FlexTitle from '../../components/layout/menus/FlexTitleComponent';
import { USERS } from '../../constants/pages';

export default {
  name: 'UsersListView',
  components: {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    SearchIcon,
    ChevronDownIcon,
    ChevronUpIcon,
    XIcon,
    FlexTitle,
    UserAvatar,
  },
  data: () => ({
    loading: true,
    searchTerm: '',
    title: USERS,
  }),
  computed: {
    ...mapState('users', ['users', 'next']),
    filteredUsers() {
      if (this.searchTerm.length > 2) {
        const term = this.searchTerm.toLowerCase();
        return filter(this.users, (user) => {
          return some([
            user?.name?.toLowerCase().includes(term),
            user?.position?.toLowerCase().includes(term),
            user?.email?.toLowerCase().includes(term)
          ]);
        });
      }

      return this.users || [];
    }
  },

  methods: {
    ...mapActions({
      getUsers: 'users/getUsers',
      loadMore: 'users/loadMore',
    }),
    async loadNext() {
      if (this.next && !this.loading) {
        this.setLoading(true)
        await this.loadMore(this.next)
        this.setLoading(false)
      }
    },
    setLoading(status) {
      this.loading = status;
    }
  },

  mounted() {
    if (!this.users.length) {
      this.getUsers().finally(() => this.setLoading(false));
    }
    let lastKnownScrollPosition = 0;
    let ticking = false;

    const onScroll = (scrollPos) => {
      if (scrollPos <= 0 && this.loading === false) {
        this.loadNext();
      }
    }

    this.onScrollView = (e) => {
      const el = e.target;
      lastKnownScrollPosition = el.scrollHeight - el.scrollTop - el.offsetHeight;
      if (!ticking) {
        window.requestAnimationFrame(function() {
          onScroll(lastKnownScrollPosition);
          ticking = false;
        });
        ticking = true;
      }
    }

    document.getElementById('app').children[0].addEventListener('scroll', this.onScrollView, { capture: false, passive: true });
    document.getElementById('main-view').addEventListener('scroll', this.onScrollView, { capture: false, passive: true });
  },
  unmounted() {
    document.getElementById('app').children[0].removeEventListener('scroll', this.onScrollView);
    document.getElementById('main-view').removeEventListener('scroll', this.onScrollView);
  }
};
</script>

<style scoped>
</style>
