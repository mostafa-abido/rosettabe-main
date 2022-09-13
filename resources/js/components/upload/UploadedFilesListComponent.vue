<!-- This example requires Tailwind CSS v2.0+ -->
<template>
  <div class="mt-0 max-w-lg px-2 mx-auto flex items-center justify-center lg:max-w-none">
    <div
      v-for="file in files"
      :key="file"
      class="flex flex-1 max-h-60 flex-col items-center overflow-hidden"
    >
      <div class="max-w-full max-h-60 flex-shrink-0">
        <div class="w-full object-cover flex">
          <template v-if="fileIs(file, 'image')">
            <img class="max-h-60 media-content" :src="file" />
          </template>
          <template v-else-if="fileIs(file, 'video')">
            <video class="flex flex-1 object-contain media-content max-h-60">
              <source :src="file" type="video/webm" />
              <source :src="file" type="video/mp4" />
              <source :src="file" type="video/ogg" />
              <source :src="file" type="video/quicktime" />
              Your browser does not support the video tag.
            </video>
          </template>
          <template v-else>
            <div class="w-32 max-h-60">
              <span>
                <DocumentIcon />
              </span>
            </div>
          </template>
        </div>
      </div>
      <!-- <span class="text-center text-xs">{{file}}</span> -->
    </div>
  </div>

  <div v-if="files.length > 0">
    <button
      type="button"
      @click="onClear()"
      class="
        relative
        cursor-pointer
        bg-gray-100
        py-1
        px-2
        border-2
        border-gray-200
        rounded-lg
        text-xs
        font-medium text-indigo-600
        hover:text-indigo-500
        focus-within:outline-none
        focus-within:ring-2
        focus-within:ring-offset-2
        focus-within:ring-indigo-500
      "
    >
      <span>Clear selection</span>
    </button>
  </div>
</template>

<script>
import { DocumentIcon } from '@heroicons/vue/outline';
import fileIs from '../../utils/fileIs';

export default {
  props: {
    files: Array,
    onClear: Function,
  },
  components: {
    DocumentIcon
  },
  methods: {
    fileIs
  }
};
</script>
