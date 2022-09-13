<template>
  <div class="w-full">
    <template v-if="[1, 3].includes(user?.data?.is)">
      <flex-title
        :title="title"
        :to="{ name: 'notes-create', params: { chatId: this.$route.params.id } }"
        :backTo="{ name: 'chat', params: { chatId: this.$route.params.id } }"
        btnText="Add Note"
      />
    </template>
    <template v-else>
      <flex-title :title="title" />
    </template>
    <div v-if="chat?.notes?.length > 0" :v-show="user?.is !== 2">
      <div class="bg-white shadow overflow-hidden rounded-md">
        <ul class="divide-y divide-gray-200">
          <li v-for="note in chat.notes" :key="note.id" class="px-6 py-4">
            <div class="flex space-x-3">
              <div class="flex-1 space-y-1">
                <div class="flex space-x-3 py-2">
                  <div class="flex-shrink-0">
                    <img
                      class="h-10 w-10 rounded-full"
                      :src="note.user.avatar"
                      alt=""
                    />
                  </div>
                  <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium text-gray-900">
                      {{ note.user.name }}
                    </p>
                    <p class="text-sm text-gray-500">
                      <time :datetime="note.created_at">{{
                        note.created_at
                      }}</time>
                    </p>
                  </div>

                  <div
                    v-show="[1, 3].includes(user?.data?.is)"
                    class="flex-shrink-0 self-center flex space-x-2"
                  >
                    <router-link
                      :to="{
                        name: 'notes-edit',
                        params: {
                          note: JSON.stringify(note),
                          chatId: this.$route.params.id,
                          id: note.id,
                        },
                      }"
                      class="
                        inline-flex
                        items-center
                        px-2
                        py-1
                        border border-transparent
                        text-sm
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
                      v-text="'Edit'"
                    >
                    </router-link>
                    <router-link
                      :to="{
                        name: 'notes-delete',
                        params: {
                          note: JSON.stringify(note),
                          chatId: this.$route.params.id,
                          id: note.id,
                        },
                      }"
                      class="
                        inline-flex
                        items-center
                        px-2
                        py-1
                        border border-transparent
                        text-sm
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
                      v-text="'Delete'"
                    >
                    </router-link>
                  </div>
                </div>

                <div class="flex items-center justify-between">
                  <h3 class="text-regular font-medium">{{ note.name }}</h3>
                </div>
                <p class="text-sm text-gray-500 whitespace-pre-wrap" v-linkified>
                  {{ note.description }}
                </p>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <empty-section
      v-else
      title="There are no any notes assosiated with the chat yet."
    />
  </div>
</template>

<script>
import FlexTitle from '../../components/layout/menus/FlexTitleComponent';
import EmptySection from '../../components/emptySection/EmptySection';
import { mapActions, mapGetters } from 'vuex';

export default {
  name: 'ListNotesView',
  components: { FlexTitle, EmptySection },
  data: () => ({ title: 'Notes' }),
  computed: {
    ...mapGetters({
      user: 'auth/user',
    }),
    chat: {
      get() {
        const chat = this.$store.getters['chats/chat'](this.$route.params.id);
        return chat;
      },
    },
  },

  methods: {
    ...mapActions({
      getChat: 'chats/getChat',
    }),
  },

  mounted() {
    if (this.$route.params.id) {
      this.getChat(this.$route.params.id);
    }
  },
};
</script>

<style scoped>
</style>
