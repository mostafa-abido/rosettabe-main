<template>
  <form action="#" method="POST" @submit.prevent="submit" class="w-full m-2">
    <div class="shadow sm:rounded-md sm:overflow-hidden w-full">
      <div class="bg-white py-6 px-4 space-y-6 sm:p-6 form-content">
        <div>
          <h3 class="text-lg leading-6 font-medium text-gray-900">
            {{ titleize(action) }} Note
            <template v-if="action !== 'add'">
              : #{{ $route.params.chatId }}
            </template>
          </h3>
        </div>

        <div v-show="action === 'delete'">
          <p class="text-regular leading-6 font-medium text-gray-400">
            Are you sure you want to delete this note?
          </p>
        </div>

        <div class="grid grid-cols-3 gap-6">
          <div class="col-span-6">
            <label
              for="street_address"
              class="block text-sm font-medium text-gray-700"
              >Name *</label
            >
            <input
              type="text"
              name="name"
              id="title"
              autocomplete="title"
              placeholder="Your Important Note"
              :disabled="action === 'delete'"
              v-model="form.name"
              class="
                mt-1
                block
                w-full
                border border-gray-300
                rounded-md
                shadow-sm
                py-2
                px-3
                disabled:opacity-50
                focus:outline-none
                focus:ring-rose-500
                focus:border-rose-500
                sm:text-sm
              "
            />
          </div>

          <div class="col-span-6">
            <label for="about" class="block text-sm font-medium text-gray-700">
              Description *
            </label>
            <div class="mt-1">
              <textarea-autosize
                :autosize="true"
                :minHeight="150"
                :maxHeight="350"
                name="description"
                v-model="form.description"
                class="
                  w-full
                  shadow-sm
                  disabled:opacity-50
                  focus:ring-rose-500
                  focus:border-rose-500
                  sm:text-sm
                  border-gray-300
                  rounded-md
                "
                :disabled="action === 'delete'"
                placeholder="Note Description"
              />
            </div>
          </div>
        </div>
      </div>
      <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
        <button
          type="button"
          @click="$router.back"
          class="
            mr-4
            bg-gray-200
            border border-transparent
            rounded-md
            shadow-sm
            py-2
            px-4
            inline-flex
            justify-center
            text-sm
            font-medium
            text-rose-600
            hover:text-rose-700
            hover:bg-gray-300
            disabled:opacity-50
            focus:outline-none
            focus:ring-2 focus:ring-offset-2 focus:ring-rose-500
          "
        >
          Cancel
        </button>
        <button
          type="submit"
          :disabled="form.name == '' || form.description == ''"
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
            disabled:opacity-50
            focus:outline-none
            focus:ring-2 focus:ring-offset-2 focus:ring-rose-500
          "
        >
          {{ action === 'delete' ? 'Delete' : 'Save' }}
        </button>
      </div>
    </div>
  </form>
</template>

<script>
import { mapActions } from 'vuex';
import startCase from 'lodash/startCase';

export default {
  props: {
    action: {
      default: 'add',
    },
  },
  data(props) {
    const action = this.action;
    let form = {
      chat_id: parseInt(this.$route.params.chatId),
      name: '',
      description: '',
    };

    if (this?.$route?.params?.note) {
      try {
        const note = JSON.parse(this.$route.params.note);
        form.name = note.name;
        form.description = note.description;
      } catch (e) {
        console.log('failed to parse a params');
        this.$outer.back();
      }
    } else {
      if (action === 'delete' || action === 'edit') {
        this.$router.replace(`/app/notes/${this.$route.params.chatId}`);
      }
    }
    return {
      form,
    };
  },

  methods: {
    ...mapActions({
      storeNote: 'note/storeNote',
      editNote: 'note/editNote',
      deleteNote: 'note/deleteNote',
    }),

    titleize: (str) => startCase(str),

    async submit() {
      switch (this.action) {
        case 'add':
          await this.storeNote(this.form);
          break;
        case 'edit':
          await this.editNote({
            chatId: this.$route.params.chatId,
            id: this.$route.params.id,
            data: this.form,
          });
          break;
        case 'delete':
          await this.deleteNote(this.$route.params.id);
          break;
        default:
          break;
      }
      await this.$router.back();
    },
  },
};
</script>
<style scoped>
@media screen and (max-width: 768px) {
  .form-content {
    overflow: hidden;
    min-height: 200px;
  }
}
</style>
