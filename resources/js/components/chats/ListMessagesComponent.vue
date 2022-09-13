<template>
  <div v-if="user?.data?.id" class="flex flex-col flex-grow p-4 pb-14">
    <div v-bind:key="message.id" v-for="message in messages">
      <div
        class="flex w-full mt-2 mr-auto justify-start max-w-fit"
        v-if="user.data.id !== message.user.id"
      >
        <div class="absolute">
          <user-avatar size="10" :src="message.user.avatar" />
        </div>
        <div class="max-w-full overflow-hidden overflow-ellipsis">
          <div class="text-sm mb-2 pl-12 h-10 flex flex-row-reverse justify-end items-center">
            <div class="text-sm font-medium text-gray-900 cursor-default">
              {{ message.user.name }}
            </div>
          </div>

          <span v-if="message.attachments.length == 1" class="text-xs text-gray-400">sent a file:</span>
          <span v-else-if="message.attachments.length > 1" class="text-xs text-gray-400">sent a files:</span>

          <ul v-show="message.attachments" class="my-2 max-w-xs space-y-2 overflow-hidden attachments-list">
            <li
              v-bind:key="attachment.filename"
              v-for="attachment in message.attachments"
            >
              <template v-if="fileIs(attachment.filename, 'image')">
                <figure class="overflow-hidden border flex flex-col">
                  <img
                    class="
                      float-left
                      max-w-screen
                      filter
                      image-preview
                    "
                    :src="attachment.filename"
                    alt="Image"
                  />
                  <figcaption
                    class="px-2 py-0.5 text-xs text-gray-400 underline truncate hover:underline"
                  >
                    <a :href="attachment.filename" target="_blank">
                      <ArrowCircleDownIcon class="inline-block w-5 h-5" />
                        {{ getFilename(attachment.filename) }}
                    </a>
                  </figcaption>
                </figure>
              </template>

              <template v-else-if="fileIs(attachment.filename, 'video')">
                <video-component
                  controls=""
                  className="text-left"
                  :source="attachment"
                />
              </template>

              <template v-else>
                <a
                  :href="attachment.filename"
                  :title="`Download ${attachment.filename}`"
                  target="_blank"
                  class="truncate block py-2 text-xs text-gray-400"
                >
                  <ArrowCircleDownIcon class="inline-block w-5 h-5" />
                  <span class="px-2 py-0.5 text-xs text-gray-400 underline truncate hover:underline">
                    {{ getFilename(attachment.filename) }}
                  </span>
                </a>
              </template>
            </li>
          </ul>
          <div
            v-if="message.message"
            class="bg-gray-200 p-3 rounded-r-lg rounded-bl-lg"
          >
            <p class="text-sm whitespace-pre-wrap" v-linkified>
              {{ message.message }}
            </p>
          </div>
          <span>
            <span class="text-xs text-gray-500 leading-none">
              {{ formatDate(message.created_at) }}
            </span>
            <div
              v-show="!!message.unreadBy.find(({ id }) => id === user.data.id)"
              class="
                mx-2
                bg-blue-500
                flex-shrink-0
                w-2.5
                h-2.5
                rounded-full
                inline-block
              "
              aria-hidden="true"
            />
          </span>
        </div>
      </div>
      <div
        class="flex w-full mt-2 ml-auto justify-end max-w-fit"
        v-if="user.data.id === message.user.id"
      >
        <div>
          <div class="text-sm text-right mb-2 pr-12 h-10 flex justify-end items-center">
            <router-link
              :to="{ name: 'user', params: { id: message.user.id } }"
              class="font-medium text-gray-900"
            >
              {{ message.user.name }}
            </router-link>
          </div>

          <span v-if="message.attachments.length == 1" class="text-xs text-gray-400">sent a file:</span>
          <span v-else-if="message.attachments.length > 1" class="text-xs text-gray-400">sent a files:</span>

          <ul v-show="message.attachments" class="my-2 max-w-xs space-y-2 overflow-hidden attachments-list">
            <li
              v-bind:key="attachment.filename"
              v-for="attachment in message.attachments"
            >
              <template v-if="fileIs(attachment.filename, 'image')">
                <figure class="overflow-hidden border flex flex-col">
                  <img
                    class="
                      float-right
                      max-w-screen
                      filter
                      image-preview
                    "
                    :src="attachment.filename"
                    alt="Image"
                  />
                  <figcaption class="px-2 py-0.5 text-xs text-gray-400 underline truncate hover:underline"
                      >
                    <a :href="attachment.filename" target="_blank"
                      >
                      <ArrowCircleDownIcon class="inline-block w-5 h-5" />
                      <span class="px-2 py-0.5 text-xs text-gray-400 underline truncate hover:underline">
                      {{ getFilename(attachment.filename) }}
                      </span>
                    </a>
                  </figcaption>
                </figure>
              </template>

              <template v-else-if="fileIs(attachment.filename, 'video')">
                <video-component
                  controls=""
                  class="text-left"
                  :source="attachment"
                />
              </template>

              <template v-else>
                <a
                  :href="attachment.filename"
                  :title="`Download ${attachment.filename}`"
                  target="_blank"
                  class="truncate block py-2 text-xs text-gray-400"
                >
                  <ArrowCircleDownIcon class="inline-block w-5 h-5" />
                  <span class="px-2 py-0.5 text-xs text-gray-400 underline truncate hover:underline">
                    {{ getFilename(attachment.filename) }}
                  </span>
                </a>
              </template>
            </li>
          </ul>
          <div
            v-if="message.message"
            class="bg-rose-600 text-white p-3 rounded-l-lg rounded-br-lg"
          >
            <p class="text-sm whitespace-pre-wrap" v-linkified>
              {{ message.message }}
            </p>
          </div>
          <span
            class="text-xs text-gray-500 leading-none"
            v-text="formatDate(message.created_at)"
          ></span>
        </div>
        <div class="absolute">
          <user-avatar size="10" :src="message.user.avatar" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import fileIs from '../../utils/fileIs';
import { ArrowCircleDownIcon } from '@heroicons/vue/solid';
import UserAvatar from '../avatar/UserAvatar';
import VideoComponent from '../video/Video';

export default {
  name: 'ListMessagesComponent',
  components: {
    VideoComponent,
    ArrowCircleDownIcon,
    UserAvatar,
  },
  props: {
    messages: Array,
  },

  methods: {
    fileIs,
    getFilename(path) {
      const parts = path.split('/');
      return decodeURIComponent(parts[parts.length - 1]);
    },
    formatDate(date) {
      return new Intl.DateTimeFormat('en-US', {
        year: 'numeric',
        month: 'numeric',
        day: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
      }).format(new Date(date));
    },
  },
  computed: {
    ...mapGetters({
      user: 'auth/user',
    }),
  },
};
</script>

<style scoped>
  .attachments-list {
    max-width: 280px;
  }

  .image-preview {
    min-width: 200px;
    min-height: 200px;
    background: gray;
  }
</style>
