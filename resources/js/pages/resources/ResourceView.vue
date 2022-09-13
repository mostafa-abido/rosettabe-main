<template>
  <div>
    <flex-title
      :to="{ name: 'resources' }"
      :title="resource.title"
      btnText="Resources"
    />

    <ul
      v-show="resource.folders"
      class="
        grid grid-cols-2
        gap-x-4 gap-y-8
        sm:grid-cols-3
        sm:gap-x-6
        lg:grid-cols-2
        xl:gap-x-8
      "
    >
      <li
        v-for="folder in resource.folders"
        :key="folder.id"
        class="col-span-1 bg-white rounded-lg shadow divide-y divide-gray-200"
      >
        <div class="w-full flex items-center justify-between p-6 space-x-6">
          <div class="flex-1 truncate">
            <div class="flex items-center space-x-3">
              <router-link
                :to="{ name: 'resource', params: { id: folder.id } }"
                class="text-gray-900 text-sm font-medium truncate"
                >{{ folder.title }}</router-link
              >
            </div>
            <p class="mt-1 text-gray-500 text-sm truncate">
              {{ folder.description }}
            </p>
          </div>
        </div>

        <dd
          v-show="folder.attachments.length"
          class="mt-1 text-sm text-gray-900"
        >
          <ul class="rounded-md divide-y divide-gray-200">
            <li
              v-for="folderAttachment in folder.attachments"
              :key="folderAttachment.id"
              class="pl-3 pr-4 py-3 flex items-center justify-between text-sm"
            >
              <div class="w-0 flex-1 flex items-center">
                <span class="ml-2 flex-1 w-0 truncate">
                  {{ folderAttachment.description }}
                </span>
              </div>
              <div class="ml-4 flex-shrink-0">
                <router-link
                  :to="{ name: 'resource', params: { id: folder.id } }"
                  class="font-medium text-rose-600 hover:text-rose-500"
                  >View</router-link
                >
              </div>
            </li>
          </ul>
        </dd>
      </li>
    </ul>
    <ul
      v-show="resource.attachments"
      class="
        mt-2
        grid grid-cols-2
        gap-x-4 gap-y-8
        sm:grid-cols-3
        sm:gap-x-6
        lg:grid-cols-2
        xl:gap-x-8
      "
    >
      <li
        v-for="attachment in resource.attachments"
        :key="attachment.id"
        class="col-span-1 bg-white rounded-lg shadow divide-y divide-gray-200"
      >
        <div class="w-full flex items-center justify-between p-6 space-x-6">
          <div class="flex-1 truncate">
            <div class="flex items-center space-x-3">
              <p class="text-gray-900 text-sm font-medium truncate">
                {{ attachment.type }}
              </p>
            </div>
            <p class="mt-1 text-gray-500 text-sm truncate">
              {{ attachment.description }}
            </p>
            <div v-if="attachment.type === 'IMAGE'">
              <img :src="attachment.file" :alt="attachment.description" />
            </div>

            <dd
              v-if="attachment.type === 'DOCUMENT'"
              class="mt-1 text-sm text-gray-900"
            >
              <ul class="rounded-md divide-y divide-gray-200">
                <li
                  class="
                    pl-3
                    pr-4
                    py-3
                    flex
                    items-center
                    justify-between
                    text-sm
                  "
                >
                  <div class="w-0 flex-1 flex items-center">
                    <span class="ml-2 flex-1 w-0 truncate">
                      {{ attachment.file }}
                    </span>
                  </div>
                  <div class="ml-4 flex-shrink-0">
                    <a
                      :href="attachment.file"
                      class="font-medium text-rose-600 hover:text-rose-500"
                      >View</a
                    >
                  </div>
                </li>
              </ul>
            </dd>
          </div>
        </div>
      </li>
    </ul>
  </div>
</template>


<script>
import { mapGetters, mapActions } from 'vuex';
import FlexTitle from '../../components/layout/menus/FlexTitleComponent';

export default {
  name: 'ResourceView',
  components: { FlexTitle },
  computed: {
    ...mapGetters({
      resource: 'resources/resource',
    }),
  },

  methods: {
    ...mapActions({
      getResource: 'resources/getResource',
    }),
  },

  mounted() {
    this.getResource(this.$route.params.id);
  },
};
</script>

<style scoped>
</style>


