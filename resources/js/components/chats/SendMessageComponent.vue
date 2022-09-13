<template>
  <form
    method="POST"
    @submit.prevent="submit()"
    enctype="multipart/form-data"
    class="border-t border-gray-100"
  >
    <div v-show="uploadBox" class="px-2 py-1 position-relative">
      <div
        v-bind="getRootProps()"
        class="flex justify-center border-1 border-gray-200 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500 rounded-xs"
      >
        <div v-if="form.files.length > 0" class="space-y-1 text-center">
          <uploaded-files-list-component
            :files="form.files"
            :onClear="clear"
          ></uploaded-files-list-component>
        </div>
        <div v-else class="space-y-1 text-center">
          <svg
            class="mx-auto h-12 w-12 text-gray-400"
            stroke="currentColor"
            fill="none"
            viewBox="0 0 48 48"
            aria-hidden="true"
          >
            <path
              d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            />
          </svg>

          <div class="flex justify-center text-sm text-gray-600 cursor-pointer">
            <label
              for="file-upload"
              class="
                relative
                font-medium
              "
            >
              <span>Press to Upload a file</span>
            </label>
              <input
                id="file-upload"
                name="file-upload"
                type="file"
                ref="file"
                accept="audio/*,video/*,image/webp,image/jpeg,image/gif,image/png,application/pdf,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                class="sr-only hidden"
                v-bind="getInputProps()"
              />
            <!-- <p class="pl-1">or drag and drop</p> -->
          </div>
          <p class="text-xs text-gray-500">Upload file up to 150MB</p>
          <p v-if="error" class="text-xs text-rose-500">{{ error }}</p>
        </div>
      </div>
    </div>

    <div class="flex flex-row items-center bg-white w-full">
      <div
        class="flex flex-row items-center w-full px-2 max-h-40 overflow-hidden"
      >
        <div class="flex pr-2">
          <button
            type="button"
            @click="showUploadBox"
            :class="[
              `
              focus:outline-none
              flex
              items-center
              justify-center
              rounded-full
              border border-gray-200
              h-10
              w-10
            `,
              uploadBox
                ? 'bg-rose-600 text-white'
                : `hover:text-rose-600 text-rose-400`,
            ]"
          >
            <svg
              class="w-5 h-5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"
              ></path>
            </svg>
          </button>
        </div>
        <div class="flex w-full p-0.5 max-h-full">
          <div class="w-full flex justify-center">
            <textarea-autosize
              ref="input"
              :min-height="40"
              :max-height="120"
              rows="1"
              v-model="form.message"
              @blur="onBlur"
              placeholder="Add new message"
              class="
                p-2
                border
                rounded-md
                border-rose-500
                w-full
                h-full
                text-sm
                items-center
                resize-none
              "
            />
          </div>
        </div>
      </div>
      <div class="flex items-center justify-center pr-2">
        <button
          :disabled="form.message.length === 0 && form.files.length === 0"
          type="submit"
          id="sendMessage"
          class="
            flex
            items-center
            justify-center
            h-10
            w-10
            rounded-full
            bg-rose-500
            hover:bg-rose-300
            text-white-800 text-white
            focus:outline-none
            disabled:opacity-50
          "
        >
          <svg
            class="w-5 h-5 transform rotate-90 -mr-px"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
            ></path>
          </svg>
        </button>
      </div>
    </div>
  </form>
</template>

<script>
import { mapActions } from 'vuex';
import { useDropzone } from 'vue3-dropzone';
import UploadedFilesListComponent from '../upload/UploadedFilesListComponent';

const MAX_FILE_SIZE = 1024 ** 2 * 150;

export default {
  name: 'SendMessageComponent',
  components: { UploadedFilesListComponent },
  props: {
    chat: {
      type: String,
      required: true,
    },
  },

  data() {
    return {
      uploadBox: false,
      error: null,
      form: {
        files: [],
        message: '',
        chat_id: this.$route.params.chatId,
      },
    };
  },

  created() {
    function onDrop(acceptFiles, rejectReasons) {
      console.log('acceptFiles', acceptFiles)
      console.log('rejectReasons', rejectReasons)

      this.error = null;

      if (rejectReasons.length > 0) {
        this.error = rejectReasons
          .map(({ file, errors }) => ({ name: file.name, errors: errors.map(({message}) => message) }))
          .map(({ name, errors }) => `${name}: ${errors.join(';')}`)
          .join('\n');
      }

      if (acceptFiles) {
        this.uploadFileList(acceptFiles)
        .then(response => {
          this.form.files = [...response.data]
          console.log(this.form.files)
        })
      }
    }

    const { getRootProps, getInputProps } = useDropzone({
      maxFiles: 10,
      maxSize: MAX_FILE_SIZE,
      accept: [ 'audio/*', 'video/*', '.webp', '.gif', 'image/jpeg' ,'image/png', 'application/pdf', 'application/msword' ,'application/vnd.openxmlformats-officedocument.wordprocessingml.document', '.zip' ],
      onDrop: onDrop.bind(this)
    })

    this.onDrop = onDrop;
    this.getRootProps = getRootProps;
    this.getInputProps = getInputProps;
  },

  methods: {
    onBlur(e) {
      const el = this.$refs.input;
      el.resize();
    },
    scrollEnd() {
      this.$nextTick(() => {
        this.$el.scrollIntoView({
          block: 'end',
          inline: 'center',
          behaviour: 'smooth',
        });
      });
    },
    ...mapActions({
      sendMessage: 'chats/sendMessage',
      getChat: 'chats/getChat',
    }),
    clear() {
      this.form.files = [];
    },
    showUploadBox() {
      this.uploadBox = !this.uploadBox;
      this.error = null;

      this.scrollEnd();
    },

    uploadFileList(files) {
      if (files.length > 0 && files.length <= 10) {
        let formData = new FormData();

        for (let i = 0; i < files.length; i++) {
          let file = files[i];

          if (file.size < MAX_FILE_SIZE) {
            formData.append('files[' + i + ']', file);
          } else {
            this.error = 'Files exceeds maximum file size';
          }
          formData.append('files[' + i + ']', file);
        }

        return axios
          .post('/api/upload',
            formData, {
              headers: {
                'Content-Type': 'multipart/form-data',
              },
            })
          .catch(function () {});
      }
    },

    async submit() {
      await this.sendMessage(this.form);

      const el = this.$refs.input;
      el.resize();

      this.form.message = '';
      this.form.files = [];

      this.uploadBox = false;
    },
  },
};
</script>

<style scoped>
</style>
