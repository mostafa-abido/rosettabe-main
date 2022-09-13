<template>
  <div class="w-full">
    <template v-for="attachment in attachments" v-bind:key="attachment.path">
      <template v-if="fileIs(attachment.path, 'image')">
        <img class="h-full media-content" :src="attachment.path" />
      </template>
      <template v-else-if="fileIs(attachment.path, 'video')">
        <video controls="" class="p-4 media-content">
          <source :src="attachment.path" type="video/webm" />
          <source :src="attachment.path" type="video/mp4" />
          <source :src="attachment.path" type="video/ogg" />
          Your browser does not support the video tag.
        </video>
      </template>
    </template>
  </div>
</template>

<script>
import fileIs from '../../utils/fileIs';
export default {
  props: {
    attachments: Array,
  },
  methods: {
    fileIs,
  },
};
</script>

<style scoped>
.media-content {
  width: 100%;
  max-height: inherit;
  margin: 0 auto;
}
</style>
