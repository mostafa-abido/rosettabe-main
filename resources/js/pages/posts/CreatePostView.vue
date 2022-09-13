<template>
  <form action="#" method="POST" @submit.prevent="submit" class="mb-16">
    <div class="sm:overflow-hidden flex flex-col h-full">
      <div
        class="
          sm:rounded-md
          border-2 border-gray-50
          shadow
          bg-white
          pt-12
          pb-6
          px-6
          space-y-6
          sm:p-6
          flex-1
        "
      >
        <div>
          <h3 class="text-lg leading-6 font-medium text-gray-900">
            Create a Post
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
            >
              Title of Your Post <span class="label-required">*</span>
            </label>
            <p class="text-sm text-gray-500">
              Required. Your post eye-catching title.
            </p>
            <input
              type="text"
              name="title"
              id="title"
              v-model="form.title"
              placeholder="Title of Your Post | required"
              class="
                mt-1
                block
                w-full
                border border-gray-300
                rounded-md
                shadow-sm
                py-2
                px-3
                focus:outline-none focus:ring-rose-500 focus:border-rose-500
                sm:text-sm
              "
            />
          </div>

          <div class="col-span-6">
            <label for="about" class="block text-sm font-medium text-gray-700">
              What's on your mind
            </label>
            <p class="text-sm text-gray-500">
              Optional. Write post content. Make it interesting to your readers.
            </p>
            <div class="mt-1">
              <textarea-autosize
                rows="3"
                :min-height="40"
                :max-height="350"
                id="body"
                name="body"
                v-model="form.body"
                placeholder="Post Description | optional"
                class="
                  shadow-sm
                  focus:ring-rose-500 focus:border-rose-500
                  mt-1
                  block
                  w-full
                  sm:text-sm
                  border-gray-300
                  rounded-md
                "
              />
            </div>
          </div>

          <div class="col-span-6">
            <label for="link" class="block text-sm font-medium text-gray-700">
              Include an URL
            </label>
            <p class="text-sm text-gray-500">
              Optional. The link in your post will be displayed after the main
              text <br />
              with the caption "Click to read more".
            </p>
            <input
              type="text"
              name="link"
              id="link"
              v-model="form.link"
              placeholder="https://englishrose.care | optional"
              class="
                mt-1
                block
                w-full
                border border-gray-300
                rounded-md
                shadow-sm
                py-2
                px-3
                focus:outline-none focus:ring-rose-500 focus:border-rose-500
                sm:text-sm
              "
            />
          </div>

          <div class="col-span-6">
            <label
              for="attachment_link"
              class="block text-sm font-medium text-gray-700"
            >
              Include an Image
            </label>
            <p class="text-sm text-gray-500">Optional. Upload an image file.</p>

            <div class="text-center mt-4">
              <uploaded-files-list-component
                :files="form.files"
                :onClear="clear"
              ></uploaded-files-list-component>
            </div>
            <div v-show="!form.files.length > 0" class="grid gap-3">
              <div
                class="col-span-6 flex items-center justify-start outline-none"
                v-bind="getRootProps()"
              >
                <label
                  for="file-upload"
                  class="relative cursor-pointer bg-white"
                >
                  <input
                    :disabled="form.image_type !== 'file'"
                    id="file-upload"
                    name="file-upload"
                    type="file"
                    ref="file"
                    placeholder="Image File"
                    accept="image/jpeg,image/png,image/gif,image/webp"
                    v-bind="getInputProps()"
                  />
                  <button
                    :disabled="imageUploading"
                    type="button"
                    class="
                      w-full
                      mt-1
                      w-full
                      block
                      border border-gray-300
                      shadow-sm
                      rounded-md
                      font-medium
                      text-white
                      bg-rose-600
                      py-2
                      px-3
                      text-left
                      hover:bg-rose-500
                      focus-within:outline-none
                      focus:border-rose-500
                      disabled:bg-rose-300 disabled:text-gray-200
                      sm:text-sm
                    "
                  >
                    {{ imageUploading ? 'Uploading...' : 'Upload a file' }}
                  </button>
                </label>
              </div>
            </div>
          </div>

          <div v-if="error" class="col-span-6">
            <p class="text-xs text-rose-500">{{ error }}</p>
          </div>
        </div>
      </div>
      <div class="w-full flex justify-between justify-center py-6 sm:px-6">
        <div>
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
              px-8
              text-sm
              font-medium
              text-rose-600
              hover:text-rose-700 hover:bg-gray-300
              disabled:opacity-50
              focus:outline-none
              focus:ring-2
              focus:ring-offset-2
              focus:ring-rose-500
            "
          >
            Cancel
          </button>
        </div>
        <div>
          <button
            type="submit"
            :disabled="!(form.title.length > 0) || imageUploading"
            class="
              bg-rose-600
              border border-transparent
              rounded-md
              shadow-sm
              py-2
              px-8
              inline-flex
              justify-center
              text-sm
              font-medium
              text-white
              hover:bg-rose-700
              disabled:opacity-50
              focus:outline-none
              focus:ring-2
              focus:ring-offset-2
              focus:ring-rose-500
            "
          >
            Submit Post
          </button>
        </div>
      </div>
    </div>
  </form>
</template>

<script>
import { mapActions } from 'vuex';
import { useDropzone } from 'vue3-dropzone';
import AttachmentFormComponent from '../../components/upload/AttachmentFormComponent';
import UploadedFilesListComponent from '../../components/upload/UploadedFilesListComponent';
import UploadImageHandler from '../../components/upload/UploadImageHandler';
import fileIs from '../../utils/fileIs';

const MAX_FILE_SIZE = 1024 ** 2 * 20;

export default {
  components: {
    UploadedFilesListComponent,
    AttachmentFormComponent,
    UploadImageHandler,
  },

  data() {
    return {
      imageUploading: false,
      error: null,
      form: {
        image_type: 'file',
        files: [],
        attachment_link: '',
        title: '',
        body: '',
      },
    };
  },

  created() {
    function onDrop(acceptFiles, rejectReasons) {
      console.log('acceptFiles', acceptFiles);
      console.log('rejectReasons', rejectReasons);

      this.imageUploading = true;
      this.error = null;

      if (rejectReasons.length > 0) {
        this.error = rejectReasons
          .map(({ file, errors }) => ({
            name: file.name,
            errors: errors.map(({ message }) => message),
          }))
          .map(({ name, errors }) => `${name}: ${errors.join(';')}`)
          .join('\n');
        this.imageUploading = false;
      }

      this.uploadFileList(acceptFiles).then((response) => {
        this.form.files = [...response.data];
        this.imageUploading = false;
      });
    }

    const { getRootProps, getInputProps } = useDropzone({
      maxFiles: 1,
      multiple: false,
      accept: ['.jpeg', '.jpg', '.png', '.gif', '.webp'],
      maxSize: MAX_FILE_SIZE,
      onDrop: onDrop.bind(this),
    });

    this.onDrop = onDrop;
    this.getRootProps = getRootProps;
    this.getInputProps = getInputProps;
  },

  methods: {
    ...mapActions({
      storePost: 'posts/storePost',
    }),

    showErrors(fields) {
      this.error = Object.values(fields).join('\n');
    },

    clear() {
      this.form.files = [];
    },
    onLinkChange() {
      if (
        this.form.attachment_link &&
        fileIs(this.form.attachment_link, 'image')
      ) {
        this.form.files = [this.form.attachment_link];
        this.form.attachment_link = '';
      }
    },
    uploadFileList(files) {
      if (files.length > 0 && files.length <= 10) {
        let formData = new FormData();

        for (let i = 0; i < files.length; i++) {
          let file = files[i];
          formData.append('files[' + i + ']', file);
        }

        return axios
          .post('/api/upload', formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          })
          .catch(function () {});
      }
    },

    async submit(e) {
      try {
        await this.storePost(this.form);
        await this.$router.replace({ path: '/app/' });
        this.notifySuccess();
      } catch (e) {
        this.showErrors(e.response.data.errors);
        this.notifyError();
      }
    },

    notifySuccess() {
      this.$notify(
        {
          group: 'app',
          type: 'success',
          title: 'Success',
          text: 'Your post will show up soon.',
        },
        3000
      );
    },

    notifyError() {
      this.$notify(
        {
          group: 'app',
          type: 'error',
          title: 'Error',
          text: 'Something went wrong 😞',
        },
        3000
      );
    },
  },
};
</script>
