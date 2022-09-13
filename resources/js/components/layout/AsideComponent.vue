<!-- This example requires Tailwind CSS v2.0+ -->
<template>
  <div class="sticky top-4 space-y-4 mt-4">
    <div class="flex items-center justify-between flex-shrink-0 h-10 px-2 pb-4">
      <span class="text-lg font-medium text-gray-900">Activity</span>
    </div>
    <ul class="divide-y divide-gray-200">
      <li
        v-for="(notification, index) in limited"
        :key="notification.id"
        class="py-4"
      >
        <div class="flex space-x-3" v-show="index <= 5">
          <user-avatar size="6" :src="user.avatar || user.data.avatar" />
          <div class="flex-1 space-y-1">
            <div class="flex items-center justify-between">
              <h3 class="text-sm font-medium">{{ user.name }}</h3>
            </div>
            <p class="text-sm text-gray-500">{{ notification.data.message }}</p>
          </div>
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import UserAvatar from '../avatar/UserAvatar';

export default {
  components: { UserAvatar },
  computed: {
    ...mapState('auth', ['user']),

    limited() {
      return this.user.notifications;
    },
  },
};
</script>
