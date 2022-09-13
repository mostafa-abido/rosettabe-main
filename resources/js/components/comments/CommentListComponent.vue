<template>
  <!-- Component Start -->
  <div
    class="
      flex flex-col flex-grow
      bg-white
      rounded-lg
      overflow-hidden
    "
  >
    <div class="border-t border-gray-200 my-1 pt-4 overflow-auto" />
    <div class="text-sm font-medium cursor-default text-gray-600">Comments</div>
    <div
      class="flex flex-col flex-grow mt-1 pb-4 overflow-auto border-t border-gray-200"
    >
      <ul v-if="post?.comments?.length > 0" class="space-y-4 py-2 overflow-auto">
        <li v-for="comment in post.comments" :key="comment.id">
          <div class="flex space-x-3">
            <router-link :to="{ name:'user', params:{id:comment.user.id}}" class="flex-shrink-0">
              <user-avatar
                size="10"
                :src="comment.user.avatar"
              />
            </router-link>
            <div class="flex-shrink overflow-hidden flex-1">
              <div class="w-full flex">
                <div>
                  <router-link :to="{ name:'user', params:{id:comment.user.id}}" class="text-sm font-medium text-gray-900">
                    <p class="font-medium text-gray-900">{{ comment.user.name }}</p>
                  </router-link>
                </div>

                <div class="flex flex-1 items-center justify-end text-xs">
                  <span class="text-gray-500 font-medium">
                    {{comment.created_at}}
                  </span>
                </div>
              </div>
              <div class="mt-1 pr-8">
                <p
                  class="text-sm text-gray-700 break-words whitespace-pre-wrap"
                  v-linkified
                >
                  {{ comment.body }}
                </p>

                <span class="text-xs text-gray-400">
                  <button v-if="comment?.user?.id === user?.data?.id" @click="() => deleteComment(post.id, comment.id)">Delete</button>
                </span>
              </div>
            </div>
          </div>
        </li>
      </ul>
      <div v-else>
        <div class="flex h-10 m-2 justify-center align-center">
          <h3 class="text-sm text-center text-bold text-gray-400">
            There are no comments yet.<br />
            Leave the comment, be the first!
          </h3>
        </div>
      </div>
    </div>
  </div>

  <div class="py-2 sticky bottom-0 bg-white border-t border-gray-200">
    <add-comment-component :postId="post.id" />
  </div>
  <!-- Component End  -->
</template>

<script>
import CommentItemComponent from './CommentItemComponent';
import AddCommentComponent from './AddCommentComponent';
import UserAvatar from '../avatar/UserAvatar';
import { mapActions, mapState } from 'vuex';
export default {
  name: 'CommentListComponent',
  props: {
    comments: {
      type: Array
    },
    post: {
      type: Object
    },
    user: {
      type: Object
    },
  },
  components: {
    UserAvatar,
    AddCommentComponent,
    CommentItemComponent,
  },
  methods: {
    ...mapActions({
      _getPost: 'posts/getPost',
      _deleteComment: 'posts/deleteComment'
    }),
    async deleteComment(postId, commentId) {
      await this._deleteComment({ postId, commentId })
      await this._getPost(postId)
    }
  },
};
</script>

<style scoped>
</style>
