<template>
  <article
    :aria-labelledby="'post-' + post.id"
    class="bg-white px-2 py-4 sm:p-4 sm:rounded-lg"
  >
    <div>
      <div class="flex space-x-3 pb-2">
        <user-avatar class="flex-shrink-0" :src="post.user.avatar" />
        <div class="min-w-0 flex-1">
          <p class="text-sm font-light text-gray-900 cursor-default">
            {{ post.user.name }}
          </p>
          <p class="text-sm font-light text-gray-500 cursor-default">
            <time :datetime="post.created_at">{{ post.created_at }}</time>
          </p>
        </div>

        <div
          v-show="post.required"
          class="min-w-0 flex-1 items-center justify-end flex"
        >
          <p
            :class="[
              post.required && !post.isReacted
                ? 'bg-pink-200 text-pink-800'
                : 'bg-pink-100 text-pink-400',
              'shadow-inner inline-flex items-center px-4 py-1 rounded-full text-xs sm:text-sm font-bold cursor-default',
            ]"
          >
            ⚠️ Action Required
          </p>
        </div>
      </div>
      <div class="pb-2">
        <span class="text-base font-bold text-gray-900">
          {{ post.title }}
        </span>
      </div>
    </div>
    <div
      :id="'post-title-body-' + post.id"
      class="mt-2"
    >
      <div
        :class="[
          `line-clamp-5 text-sm text-gray-700 overflow-hidden
           whitespace-pre-wrap break-words`,
          post.user.is === 1 ? '' : 'post-preview-content',
        ]"
        v-html="processLinks(post.body, { preview: true })"
      />
    </div>

    <div
      class="
        mt-2
        overflow-hidden
        post-preview-attachment-content
        pointer-events-none
      "
    >
      <post-attachments
        :attachments="post.attachments"
        class="post-preview-attachment-content-item"
      ></post-attachments>
    </div>

    <div class="mt-6 flex justify-between space-x-8">
      <div class="flex space-x-6">
        <span class="inline-flex items-center text-sm">
          <button
            class="inline-flex space-x-2 px-2 text-gray-400 hover:text-gray-500"
          >
            <ThumbUpIcon
              v-show="!post.isLiked"
              @click="like"
              class="h-5 w-5"
              aria-hidden="true"
            />
            <ThumbUpIcon
              v-show="post.isLiked"
              @click="unlike"
              class="text-rose-500 hover:text-rose-600 h-5 w-5"
              aria-hidden="true"
            />
            <span class="font-medium text-gray-900">{{ likesCount }}</span>
            <span class="sr-only">likes</span>
          </button>
        </span>
        <span class="inline-flex items-center text-sm">
          <ChatAltIcon class="h-5 w-5" aria-hidden="true" />
          <span class="font-medium text-gray-900">{{
            post.comments.length
          }}</span>
          <span class="sr-only">replies</span>
        </span>
      </div>
    </div>
  </article>
</template>

<script>
import { mapActions } from 'vuex';
import { ChatAltIcon, ThumbUpIcon } from '@heroicons/vue/solid';

import PostAttachments from '../posts/PostAttachments';
import UserAvatar from '../avatar/UserAvatar';
import processLinks from '../../utils/processLinks';

export default {
  name: 'PostCardComponent',

  components: {
    ThumbUpIcon,
    ChatAltIcon,
    PostAttachments,
    UserAvatar,
  },

  props: {
    post: Object,
  },

  data() {
    return {
      likesCount: this.post.likes.length,
    };
  },

  methods: {
    ...mapActions({
      likePost: 'posts/likePost',
    }),

    processLinks,

    like() {
      this.likePost(this.post.id);
      this.post.isLiked = true;
      this.incrementLikeCounter();
    },

    unlike() {
      this.likePost(this.post.id);
      this.post.isLiked = false;
      this.downLikeCounter();
    },

    incrementLikeCounter: function () {
      this.likesCount += 1;
    },

    downLikeCounter: function () {
      this.likesCount -= 1;
    },
  },
};
</script>

<style scoped>
.post-preview-content {
  max-height: 100px;
}

.post-preview-attachment-content {
  max-height: 200px;
}

.post-preview-attachment-content-item {
  max-height: 200px;
}
</style>
