<template>
  <figure :class="[className, 'overflow-hidden border max-w-screen']">
    <video :controls="controls" class="w-full h-full">
      <source :src="src" type="video/webm" />
      <source :src="src" type="video/mp4" />
      <source :src="src" type="video/ogg" />
      Your browser does not support the video tag.
    </video>
    <a v-show="showCaption" :href="src" target="_blank"
      >
      <ArrowCircleDownIcon class="inline-block w-5 h-5" />
      <figcaption class="px-2 py-0.5 text-xs text-gray-400 underline truncate hover:underline">
        {{ filename }}
      </figcaption>
    </a>
  </figure>
</template>

<script>
import fileIs from '../../utils/fileIs';
import { ArrowCircleDownIcon } from '@heroicons/vue/solid';

const getFilename = (path) => {
  const parts = path.split('/');
  return parts[parts.length - 1];
};

export default {
  props: {
    className: {
      type: String,
      default: '',
    },
    controls: {
      type: String,
      default: '',
    },
    class: {
      type: String,
      default: '',
    },
    pathKey: {
      type: String,
      default: 'filename',
    },
    source: Object,
    showCaption: Boolean,
  },
  components: {
    ArrowCircleDownIcon
  },
  computed: {
    filename() {
      if (this.source) {
        return getFilename(this.source[this.pathKey]);
      }
      return null;
    },
    src() {
      if (this.source) {
        return this.source[this.pathKey];
      }
      return null;
    },
  },
  methods: {
    fileIs,
    getFilename,
  },
};
</script>

<style scoped>
</style>
