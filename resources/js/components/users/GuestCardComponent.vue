<template>
  <div class="flex-1 min-w-0 flex flex-start flex-col">
    <!-- Extend touch target to entire panel -->
    <div class="space-y-2">
      <p class="text-xl font-medium text-gray-900">
        <span>{{ user.fname }} {{ user.lname }}</span>
        <span class="ml-2">{{ '(Home Care)' }}</span>
      </p>

      <p class="text-lg text-red-400  text-gray-500 truncate">
          <span>{{ user.address }}</span>
      </p>

      <span class="text-md text-center text-gray-500 truncate">
          <span class="mr-2">Home Phone:</span>
          <a :href="`tel:${ user.phone }`" class="text-red-400">{{ '234234234' }}</a>
      </span>

      <div class="flex">
        <div class="flex flex-1 items-start">
          <div v-if="user.photo1" class="border-2">
            <img :src="user.photo1">
          </div>
        </div>
        <div class="flex flex-1 justify-center items-center">
          <div v-if="!user.photo1" class="border-2 max-width-img">
            <img :src="user.photo2">
          </div>
        </div>
      </div>

      <div class="row items-start">
        <h4 class="font-semibold">Wellness Notes</h4>
        <p class="text-slate-200 text-sm">
         dummy text
        </p>
      </div>

      <div class="row items-start">
        <h4 class="font-semibold">Life Story</h4>
        <p class="text-slate-200 text-sm">
          {{ user.life_story }}
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import UserAvatar from '../avatar/UserAvatar';

export default {
  name: 'GuestCardComponent',
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
