<template>
  <div class="flex space-x-3">
    <div class="min-w-0 flex-1 mx-1">
      <form method="POST" @submit.prevent="submit">
        <textarea-autosize
          :max-height="350"
          id="comment"
          name="comment"
          v-model="body"
          class="
            shadow-sm
            block
            w-full
            focus:ring-rose-500
            focus:border-rose-500
            sm:text-sm
            border border-gray-300
            rounded-md
          "
          placeholder="Leave a comment..."
        />
        <div class="mt-2 flex items-center justify-end">
          <button
            :disabled="body.length == 0"
            type="submit"
            class="
              inline-flex
              items-center
              justify-center
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
              focus:ring-2 focus:ring-offset-2 focus:ring-rose-500
              disabled:opacity-50
            "
          >
            Comment
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { mapActions, mapState } from 'vuex';

export default {
  name: 'AddCommentComponent',

  computed: {
    ...mapState('posts', ['post']),
    ...mapState('auth', ['user']),
  },

  props: {
    postId: Number
  },

  data() {
    return {
      body: '',
    };
  },

  methods: {
    ...mapActions({
      storeComment: 'posts/storeComment',
      getPost: 'posts/getPost',
    }),

    async submit() {
      await this.storeComment({
        body: this.body,
        postId: this.postId,
        userId: this.user.id,
      });

      this.body = '';

      this.getPost(this.postId);
    },
  },
};
</script>

<style scoped>
</style>
