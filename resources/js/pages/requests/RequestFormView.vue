<template>
  <div>
    <flex-title :title="title" />
    <form action="#" method="POST" @submit.prevent="submit">
      <div class="sm:overflow-hidden">
        <div class="shadow bg-white rounded-lg m-2 py-6 px-4 space-y-6 sm:p-6">
          <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">
              Create Request
            </h3>
            <p class="mt-1 text-sm text-gray-500">
              This information will be displayed publicly so be careful what you
              share.
            </p>
          </div>

          <div class="grid grid-cols-3 gap-6">
            <div class="col-span-6">
              <label
                for="street_address"
                class="block text-sm font-medium text-gray-700"
                >Title</label
              >
              <input
                type="text"
                name="title"
                id="title"
                autocomplete="title"
                placeholder="Request Title"
                v-model="this.form.title"
                class="
                  mt-1
                  block
                  w-full
                  border border-gray-300
                  rounded-md
                  shadow-sm
                  py-2
                  px-3
                  focus:outline-none
                  focus:ring-rose-500
                  focus:border-rose-500
                  sm:text-sm
                "
              />
            </div>

            <div class="col-span-6">
              <label
                for="about"
                class="block text-sm font-medium text-gray-700"
              >
                Description
              </label>
              <div class="mt-1">
                <textarea-autosize
                  rows="3"
                  :min-height="40"
                  :max-height="350"
                  id="body"
                  name="body"
                  v-model="this.form.body"
                  class="
                    shadow-sm
                    focus:ring-rose-500
                    focus:border-rose-500
                    mt-1
                    block
                    w-full
                    sm:text-sm
                    border-gray-300
                    rounded-md
                  "
                  placeholder="Request Description"
                />
              </div>
            </div>
          </div>
        </div>
        <div
          v-show="form.title && form.body !== ''"
          class="px-4 py-3 bg-gray-100 text-right sm:px-6"
        >
          <button
            type="submit"
            class="
              bg-rose-600
              border border-transparent
              rounded-md
              shadow-sm
              py-2
              px-4
              inline-flex
              justify-center
              text-sm
              font-medium
              text-white
              hover:bg-rose-700
              focus:outline-none
              focus:ring-2 focus:ring-offset-2 focus:ring-rose-500
            "
          >
            Save
          </button>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import FlexTitle from '../../components/layout/menus/FlexTitleComponent';
import { REQUESTS } from '../../constants/pages';
import { mapActions } from 'vuex';

export default {
  name: 'RequestFormView',
  components: {
    FlexTitle,
  },
  data() {
    return {
      title: REQUESTS,
      form: {
        title: '',
        body: '',
        status: 'pending',
      },
    };
  },
  methods: {
    ...mapActions({
      storeRequest: 'requests/storeRequest',
    }),

    async submit() {
      await this.storeRequest(this.form);
      await this.$router.replace({ path: '/app/' });
    },
  },
};
</script>

