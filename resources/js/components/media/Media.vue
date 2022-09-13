<template>
  <div>
    <div
      id="content"
      class="pb-8 min-h-screen"
      :style="isExpanded ? 'display:block' : 'display:none'"
    >
      <div class="w-full rounded-lg shadow flex-none py-4 bg-white">
        <div class="mx-4 px-2 py-2 my-2 border-b-2 border-gray-300">
          <ol class="flex items-center">
            <li @click="setCurrentPath(-1)">
              <div>
                <a href="#" class="text-gray-400 hover:text-gray-500">
                  <HomeIcon class="flex-shrink-0 h-5 w-5" aria-hidden="true" />
                  <span class="sr-only">Home</span>
                </a>
              </div>
            </li>
            <li v-bind:key="i" v-for="(folder, i) in getCurrentPath()">
              <div class="flex items-center">
                <svg
                  class="flex-shrink-0 h-5 w-5 text-gray-600"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                  aria-hidden="true"
                >
                  <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                </svg>
                <button
                  @click="setCurrentPath(i)"
                  :disabled="getCurrentPath().length - 1 === i"
                  class="
                    text-sm
                    font-medium
                    text-gray-500
                    hover:text-gray-700
                  "
                >
                  {{ folder }}
                </button>
              </div>
            </li>
          </ol>
        </div>
        <div
          v-if="!is_loading && files.length > 0"
          class="flex-none
            grid grid-flow-row grid-cols-3
            sm:grid-cols-3
            gap-2 lg:gap-4"
        >
          <template v-bind:key="file.path" v-for="file in files">
            <div
              @click="openFile(file, $event)"
              class="
                flex flex-col
                items-center
                justify-center
                overflow-hidden
                mx-2
                mt-2
                cursor-pointer
              "
            >
              <div class="w-8 h-8">
                <template v-if="fileIs(file, 'folder')">
                  <FolderIcon />
                </template>
                <template v-else-if="fileIs(file, 'image')">
                  <span
                    class="bg-no-repeat bg-center bg-contain"
                    :style="imgIcon(file.path)"
                  />
                </template>
                <template v-else-if="fileIs(file, 'video')">
                  <VideoComponent
                    className="pointer-events-none flex justify-content h-full"
                    :source="{ filename: file.path }"
                    :showCaption="false"
                    :controls="null"
                  />
                </template>
                <template v-else>
                  <svg v-if="file.type !== 'link'"
                    fill="#000000"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 50 50"
                  >
                    <path
                      d="M 7 2 L 7 48 L 43 48 L 43 14.59375 L 42.71875 14.28125 L 30.71875 2.28125 L 30.40625 2 Z M 9 4 L 29 4 L 29 16 L 41 16 L 41 46 L 9 46 Z M 31 5.4375 L 39.5625 14 L 31 14 Z M 15 22 L 15 24 L 35 24 L 35 22 Z M 15 28 L 15 30 L 31 30 L 31 28 Z M 15 34 L 15 36 L 35 36 L 35 34 Z"
                    />
                  </svg>
                  <ExternalLinkIcon v-else />
                </template>
              </div>
              <div class="w-full px-2 py-2 cursor-pointer">
                <h4
                  class="
                    text-xs
                    text-center
                    font-medium
                    text-gray-900
                    line-clamp-3
                    break-all
                  "
                  style="min-height: 48px; max-height: 48px;"
                  :title="file?.filename || file?.name"
                >
                  {{ file?.filename || file?.name.replace(/\.\w+$/, '') }}
                </h4>
              </div>
            </div>
          </template>
        </div>
        <div
          v-else
          class="
            mx-4
            py-8
            border-2 border-solid border-gray-100
            rounded-lg
            flex
            justify-center
            align-center
          "
        >
          <div class="text-center text-xs text-gray-400" v-if="is_loading">
            <p>Loading...</p>
          </div>
          <div class="text-center" v-else-if="files.length == 0">
            <h3 class="text-gray-400 text-sm flex flex-col justify-center items-center">
              <FolderOpenIcon class="w-10" />
              No files in folder
            </h3>
          </div>
        </div>
      </div>
    </div>

    <!-- Image Modal -->
    <Dialog
      class="
        absolute
        top-0
        bottom-0
        z-10
        w-full
        flex
        justify-center
        align-center
      "
      :open="isOpen"
      @close="setIsOpen(false)"
    >
      <DialogOverlay
        class="fixed top-0 bottom-0 right-0 w-full z-0 bg-black opacity-50"
      />
      <div
        class="flex justify-between flex-col bg-white px-4 py-6 z-10 relative"
      >
        <DialogTitle
          class="py-1 text-center text-regular text-bold cursor-default"
        >
          {{ selected_file.name }}
        </DialogTitle>
        <a
          v-on:click="setIsOpen(false)"
          href="#"
          class="absolute w-5 h-5 top-4 right-4 cursor-pointer"
          ><XIcon
        /></a>
        <div class="flex justify-center align-center">
          <img
            :src="selected_file.path"
            class="px-4"
            style="max-height: 80vh; margin: 0 auto"
          />
        </div>
        <div class="relative flex justify-center py-2 my-4">
          <a
            class="text-blue-400 underline"
            :href="selected_file.path"
            target="_blank"
            >Download</a
          >
        </div>
      </div>
    </Dialog>
  </div>
</template>
<script>
import { ref } from 'vue';
import {
  TransitionChild,
  TransitionRoot,
  Dialog,
  DialogOverlay,
  DialogTitle,
  DialogDescription,
} from '@headlessui/vue';
import {
  HomeIcon,
  ChevronRightIcon,
  CollectionIcon,
  ExternalLinkIcon,
  FolderIcon,
  FolderOpenIcon,
  FilmIcon,
  DocumentIcon,
  ArchiveIcon,
  XIcon,
} from '@heroicons/vue/outline';
import VideoComponent from '../video/Video';
import fileIs from '../../utils/fileIs';
const getFilemanagerAPIPath = (path) => `/api/filemanager/${path}`;

export default {
  props: {
    basePath: {
      type: String,
      default: '',
    },
    filename: {
      type: String,
      default: null,
    },
    allowMultiSelect: {
      type: Boolean,
      default: false,
    },
    maxSelectedFiles: {
      type: Number,
      default: 0,
    },
    minSelectedFiles: {
      type: Number,
      default: 0,
    },
    showFolders: {
      type: Boolean,
      default: true,
    },
    showToolbar: {
      type: Boolean,
      default: true,
    },
    allowUpload: {
      type: Boolean,
      default: true,
    },
    allowMove: {
      type: Boolean,
      default: true,
    },
    allowDelete: {
      type: Boolean,
      default: true,
    },
    allowCreateFolder: {
      type: Boolean,
      default: true,
    },
    allowRename: {
      type: Boolean,
      default: true,
    },
    allowCrop: {
      type: Boolean,
      default: true,
    },
    allowedTypes: {
      type: Array,
      default: function () {
        return [];
      },
    },
    preSelect: {
      type: Boolean,
      default: true,
    },
    element: {
      type: String,
      default: '',
    },
    details: {
      type: Object,
      default: function () {
        return {};
      },
    },
    expanded: {
      type: Boolean,
      default: true,
    },
  },
  components: {
    HomeIcon,
    ExternalLinkIcon,
    XIcon,
    FilmIcon,
    DocumentIcon,
    ArchiveIcon,
    ChevronRightIcon,
    CollectionIcon,
    FolderIcon,
    FolderOpenIcon,
    TransitionChild,
    TransitionRoot,
    Dialog,
    DialogOverlay,
    DialogTitle,
    DialogDescription,
    VideoComponent,
  },
  data: function () {
    return {
      isOpen: ref(false),
      current_folder: '',
      selected_files: [],
      files: [],
      is_loading: true,
      hidden_element: null,
      isExpanded: this.expanded,
      modals: {
        new_folder: {
          name: '',
        },
        move_files: {
          destination: '',
        },
      },
    };
  },
  computed: {
    selected_file: function () {
      return this.selected_files[0];
    },
  },
  methods: {
    setIsOpen(value = true) {
      this.isOpen = value;
    },
    getFiles: async function () {
      var vm = this;
      vm.is_loading = true;

      axios
        .post(getFilemanagerAPIPath('files'), {
          folder: vm.current_folder,
          details: vm.details,
        })
        .then(function ({ data }) {
          vm.files = [];
          for (var i = 0, file; (file = data[i]); i++) {
            if (vm.filter(file)) {
              vm.files.push(file);
            }
          }
          vm.selected_files = [];
          if (vm.preSelect && data.length > 0) {
            vm.selected_files.push(data[0]);
          }
          vm.is_loading = false;
        });
    },
    selectFile: function (file, e) {
      if ((!e.ctrlKey && !e.metaKey && !e.shiftKey) || !this.allowMultiSelect) {
        this.selected_files = [];
      }

      if (
        e.shiftKey &&
        this.allowMultiSelect &&
        this.selected_files.length == 1
      ) {
        var index = null;
        var start = 0;
        for (var i = 0, cfile; (cfile = this.files[i]); i++) {
          if (cfile === this.selected_file) {
            start = i;
            break;
          }
        }

        var end = 0;
        for (var i = 0, cfile; (cfile = this.files[i]); i++) {
          if (cfile === file) {
            end = i;
            break;
          }
        }

        for (var i = start; i < end; i++) {
          index = this.selected_files.indexOf(this.files[i]);
          if (index === -1) {
            this.selected_files.push(this.files[i]);
          }
        }
      }

      index = this.selected_files.indexOf(file);
      if (index === -1) {
        this.selected_files.push(file);
      }

      if (this.selected_files.length == 1) {
        var vm = this;
        this.$nextTick(function () {
          if (vm.fileIs(vm.selected_file, 'video')) {
            vm.$refs.videoplayer.load();
          } else if (vm.fileIs(vm.selected_file, 'audio')) {
            vm.$refs.audioplayer.load();
          }
        });
      }
    },
    openFile: function (file, e) {
      this.selectFile(file, e);

      if (file.type == 'link') {
        window.open(file.content);
      } else if (file.type == 'folder') {
        this.current_folder += file.name + '/';
        this.getFiles();
      } else if (this.hidden_element) {
        this.addFileToInput(file);
      } else {
        if (this.fileIs(file.path, 'image')) {
          this.setIsOpen(true);
        } else {
          window.open(file.path);
        }
      }
    },
    isFileSelected: function (file) {
      return this.selected_files.includes(file);
    },
    fileIs,
    getCurrentPath: function () {
      var path = this.current_folder
        .replace(this.basePath, '')
        .split('/')
        .filter(function (el) {
          return el != '';
        });

      return path;
    },
    setCurrentPath: async function (i) {
      if (i == -1) {
        this.current_folder = '/';
      } else {
        var path = this.getCurrentPath();
        path.length = i + 1;
        this.current_folder = (this.basePath == '/' ? '' : this.basePath) + path.join('/') + '/';
      }

      await this.getFiles();
    },
    filter: function (file) {
      if (this.allowedTypes.length > 0) {
        if (file.type != 'folder') {
          for (var i = 0, type; (type = this.allowedTypes[i]); i++) {
            if (file.type.includes(type)) {
              return true;
            }
          }
        }
      }

      if (file.type == 'folder' && this.showFolders) {
        return true;
      } else if (file.type == 'folder' && !this.showFolders) {
        return false;
      }
      if (this.allowedTypes.length == 0) {
        return true;
      }

      return false;
    },
    getSelectedFiles: function () {
      if (!this.allowMultiSelect) {
        var content = [];
        if (this.hidden_element.value != '') {
          content.push(this.hidden_element.value);
        }

        return content;
      } else {
        return JSON.parse(this.hidden_element.value);
      }
    },
    bytesToSize: function (bytes) {
      var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
      if (bytes == 0) return '0 Bytes';
      var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
      return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
    },
    getFileName: function (name) {
      var name = name.split('/');
      return name[name.length - 1];
    },
    imgIcon: function (path) {
      path = path.replace(/\\/g, '/');
      return (
        'background-image: url("' +
        path +
        '");display:inline-block; width:100%; height:100%;'
      );
    },
    dateFilter: function (date) {
      if (!date) {
        return null;
      }
      var date = new Date(date * 1000);

      var month = '0' + (date.getMonth() + 1);
      var minutes = '0' + date.getMinutes();
      var seconds = '0' + date.getSeconds();

      var dateFormated =
        date.getFullYear() +
        '-' +
        month.substr(-2) +
        '-' +
        date.getDate() +
        ' ' +
        date.getHours() +
        ':' +
        minutes.substr(-2) +
        ':' +
        seconds.substr(-2);

      return dateFormated;
    },
  },

  getLinks: async function () {
    var vm = this;
    vm.is_loading = true;

    axios
      .post(getFilemanagerAPIPath('links'))
      .then(function ({ data }) {
        vm.files = [];
        for (var i = 0, file; (file = data[i]); i++) {
          if (vm.filter(file)) {
            vm.files.push(file);
          }
        }
        vm.selected_files = [];
        if (vm.preSelect && data.length > 0) {
          vm.selected_files.push(data[0]);
        }
        vm.is_loading = false;
      });
  },
  mounted: function () {

    this.getFiles();
    var vm = this;

    if (this.element != '') {
      this.hidden_element = document.querySelector(this.element);
      if (!this.hidden_element) {
        console.error('Element "' + this.element + '" could not be found.');
      } else {
        if (this.maxSelectedFiles > 1 && this.hidden_element.value == '') {
          this.hidden_element.value = '[]';
        }
      }
    }

    //Key events
    this.onkeydown = function (evt) {
      evt = evt || window.event;
      if (evt.keyCode == 39) {
        evt.preventDefault();
        for (var i = 0, file; (file = vm.files[i]); i++) {
          if (file === vm.selected_file) {
            i = i + 1; // increase i by one
            i = i % vm.files.length;
            vm.selectFile(vm.files[i], evt);
            break;
          }
        }
      } else if (evt.keyCode == 37) {
        evt.preventDefault();
        for (var i = 0, file; (file = vm.files[i]); i++) {
          if (file === vm.selected_file) {
            if (i === 0) {
              i = vm.files.length;
            }
            i = i - 1;
            vm.selectFile(vm.files[i], evt);
            break;
          }
        }
      } else if (evt.keyCode == 13) {
        evt.preventDefault();
        if (evt.target.tagName != 'INPUT') {
          vm.openFile(vm.selected_file, null);
        }
      }
    };
  },
};
</script>
<style>
.dd-placeholder {
  flex: 1;
  width: 100%;
  min-width: 200px;
  max-width: 250px;
}
</style>
