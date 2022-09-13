<template>
  <div class="flex-1 min-w-0 flex flex-start flex-col">
    <!-- Extend touch target to entire panel -->
    <div class="space-y-2">
      <p class="text-sm font-medium text-gray-900">
        <span>{{ user.name }} {{ user.surname}}</span>
        <template v-if="user.phone"> | <span class="text-sm text-center text-gray-500 truncate">
          <a :href="`tel:${ user.phone }`">{{ user.phone }}</a>
        </span>
        </template>
      </p>

      <p v-if="user.email" class="text-sm text-gray-500 truncate">
        <a :href="`mailto:${ user.email }`">{{ user.email }}</a>
      </p>
      <p class="text-sm text-gray-500 truncate">
        {{ user.position }}
      </p>
      <div class="flex py-4 space-x-2">
        <div class="flex flex-1 justify-center items-center">
          <div v-if="user.avatar" class="border-2 max-width-img">
            <img :src="user.avatar">
          </div>
        </div>
        <div class="flex flex-1 justify-center items-center">
          <div v-if="user.alt_image" class="border-2 max-width-img">
            <img :src="user.alt_image">
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import UserAvatar from '../avatar/UserAvatar';

export default {
  name: 'UserCardComponent',
  components: {
    UserAvatar
  },
  props: {
    user: Object,
  },
};
</script>

<style scoped>
.max-width-img {
  max-width: 120px;
}
</style>
