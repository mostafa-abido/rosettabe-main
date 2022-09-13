<template>
  <div v-linkified v-linkified:options="options" v-html="resultHtml"></div>
</template>
<script>
import processLinks from '../../utils/processLinks';
export default {
  props: {
    html: String,
  },
  data() {
    return {
      options: {},
    };
  },
  computed: {
    resultHtml() {
      return processLinks(this.html, { controls: true });
    },
  },
};
</script>
