<template>
  <div class="bg-white shadow sm:rounded-lg px-4 pb-2 pt-0 sm:px-6">
    <div>
      <div class="flex space-x-3 pt-2">
        <router-link :to="{ name:'user', params:{id:post.user.id}}" class="flex-shrink-0">
          <user-avatar :src="post?.user?.avatar" />
        </router-link>

        <div class="min-w-0 h-10 flex-1 flex flex-col justify-center">
          <router-link :to="{ name:'user', params:{id:post.user.id}}" class="text-sm font-medium text-gray-900">
            {{ post?.user?.name }}
          </router-link>

          <p class="text-xs text-gray-500 cursor-default">
            <time :datetime="post.created_at">{{ post.created_at }}</time>
          </p>
        </div>

        <div
          v-show="post.required"
          class="min-w-0 flex-2 items-center justify-end flex"
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

      <div class="border-t border-gray-200 mt-2" />

      <h2 class="py-2 text-lg leading-6 font-medium text-gray-900 cursor-default">
        {{ post.title }}
      </h2>
    </div>

      <div>
        <div class="sm:col-span-2 py-2">
          <linkify-text
            class="text-sm text-gray-900 break-words post-content-body"
            :html="post.body"
          />
        </div>

        <div v-if="!post?.link" class="sm:col-span-2 w-full flex justify-center align-center overflow-hidden rounded-sm">
          <post-attachments
            v-if="post?.attachments?.length > 0"
            :attachments="post?.attachments"
          />
        </div>
        <div v-else class="sm:col-span-2 w-full flex justify-center align-center overflow-hidden rounded-sm flex-col">
          <a target="_blank" rel="noopener noreferrer" :href="post.link.match(/^https?\:\/\//gi) ? post.link : `http://${post.link}`" class="text-xs text-center font-medium underline py-2">
            <span class="text-center">
              <LinkIcon
                class="h-4 w-4 mr-2 inline-block"
                aria-hidden="true"
              />
              <span>
              {{post?.attachments?.length > 0 ? "Click the picture below for more" : "Click here to read more" }}
              </span>
            </span>
          </a>
          <a target="_blank" rel="noopener noreferrer" :href="post.link">
            <post-attachments
              v-if="post?.attachments?.length > 0"
              :attachments="post?.attachments"
            />
          </a>
        </div>
      </div>

      <div
        v-show="post?.required"
        class="mt-2 sm:col-span-2 w-full flex justify-center align-center"
      >
        <button
          type="button"
          @click="complete"
          :disabled="post?.isReacted"
          v-text="post?.isReacted ? 'Thank you for completing this action item' : 'Complete'"
          :title="
            post?.isReacted
              ? 'You\'ve already completed it.'
              : 'Mark post as done'
          "
          class="
            inline-flex
            items-center
            justify-center
            px-3
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
            focus:ring-2 focus:ring-offset-2 focus:ring-rose-500
            disabled:opacity-50
          "
        />
      </div>

      <div class="flex justify-between space-x-8 pt-2">
        <div class="flex space-x-6">
          <span class="inline-flex items-center text-sm space-x-2">
            <button class="inline-flex items-center justify-center text-gray-400 py-1" @click="toggleLike">
              <span>
                <ThumbUpIcon
                  v-show="!post.isLiked"
                  class="text-gray-400 h-4 w-4"
                  aria-hidden="true"
                />
                <ThumbUpIcon
                  v-show="post.isLiked"
                  class="text-rose-500 h-4 w-4"
                  aria-hidden="true"
                />
              </span>

              <span class="pl-1 font-medium text-xs text-gray-900 cursor-default">
                {{likesCount}}
              </span>
            </button>
            <span class="inline-flex items-center justify-center text-gray-400">
              <ChatAltIcon class="h-4 w-4 text-gray-400" aria-hidden="true" />
              <span class="pl-1 font-medium text-xs text-gray-900 cursor-default">
                {{post?.comments?.length}}
              </span>
            </span>
          </span>
        </div>
      </div>

      <div>
        <comment-list-component
          :comments="post.comments"
          :post="post"
          :user="user"
        />
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

import { ChatAltIcon, LinkIcon, ThumbUpIcon } from '@heroicons/vue/solid';
import CommentListComponent from '../comments/CommentListComponent';

import PostAttachments from './PostAttachments';
import UserAvatar from '../avatar/UserAvatar';
import LinkifyText from '../linkify-text/LinkifyText';

export default {
  name: 'PostCardComponent',
  components: {
    CommentListComponent,
    LinkifyText,
    UserAvatar,
    PostAttachments,
    LinkIcon,
    ThumbUpIcon,
    ChatAltIcon,
  },

  props: {
    post: Object,
  },

  data() {
    return {
      likesCount: this.post.likes.length,
    };
  },

  computed: {
    ...mapGetters({
      user: 'auth/user',
    })
  },

  methods: {
    ...mapActions({
      completePost: 'posts/completePost',
      likePost: 'posts/likePost',
    }),

    async complete() {
      try {
        await this.completePost(this.post.id);
        this.notifySuccess();
        this.post.isReacted = true;
      } catch (err) {
        this.notifyError();
      }
    },

    async toggleLike() {
      const { data: { count, isLiked } } = await this.likePost(this.post.id);

      this.post.isLiked = isLiked;
      this.likesCount = count;
    },

    notifySuccess() {
      this.$notify({
        group: "app",
        type: "success",
        title: "Success",
        text: "Thank you for completing this action item"
      }, 3000);
    },

    notifyError() {
      this.$notify({
        group: "app",
        type: "error",
        title: "Error",
        text: "Something went wrong 😞",
      }, 3000);
    }
  },
};
</script>

<style scoped>
  .post-content-body p {
    margin-bottom: 2rem;
  }
</style>
