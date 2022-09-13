<template>
  <div class="relative z-5 bg-gray-100">
    <div
      class="
        flex
        items-center
        justify-between
        flex-shrink-0
        space-x-2
        pt-4
        pb-4
      "
    >
      <button
        v-if="(backTo || backHandler) && !noBack"
        @click=" !backTo ? backHandler() : $router.push(backTo)"
        class="
          absolute left-0
          flex
          items-center
          bg-white
          text-xs
          font-medium
          pl-2
          py-1
          pr-3
          rounded-md
          shadow-sm
          border border-transparent
        "
      >
        <ChevronLeftIcon class="w-4 h-4 inline" />
        <div>Back</div>
      </button>
      <div :class="['flex flex-1',`justify-${justify}`]">
        <span
          :class="['text-lg cursor-default font-medium text-gray-900 block h-7']"
          v-text="title"
        />
      </div>

      <router-link
        v-if="to"
        :to="to"
        class="
          absolute right-0
          ml-6
          inline-flex
          items-center
          px-2
          py-1
          border border-transparent
          text-xs
          font-medium
          rounded-md
          shadow-sm
          text-white
          bg-rose-600
          hover:bg-rose-700
          focus:outline-none
          focus:ring-rose-500 focus:ring-rose-500
          focus:bg-rose-600
        "
        v-text="btnText || btnText"
      >
      </router-link>
    </div>
  </div>
</template>

<script>
import { ChevronLeftIcon } from '@heroicons/vue/solid';
export default {
  name: 'FlexTitleComponent',
  components: { ChevronLeftIcon },
  props: {
    noBack: Boolean,
    justify: {
      default: 'center',
      type: String
    },
    backTo: {
      default: undefined,
      type: Object,
    },
    backHandler: {
      default: () => window.history.state.position > 1 ? window.history.back() : window.location.pathname = '/',
      type: Function
    },
    title: '',
    button: '',
    btnText: '',
    to: {},
  },
};
</script>

<style scoped>
</style>
