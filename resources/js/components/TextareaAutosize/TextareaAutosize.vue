<template>
  <textarea
    :style="computedStyles"
    v-model="val"
    @focus="resize"
    @blur="resize"
    ref="textarea"
  ></textarea>
</template>
<script>
export default {
  name: 'TextareaAutosize',
  model: {
    prop: 'value',
    event: 'change',
  },
  props: {
    modelValue: {
      type: String,
      default: '',
    },
    autosize: {
      type: Boolean,
      default: true,
    },
    minHeight: {
      type: [Number],
      default: null,
    },
    maxHeight: {
      type: [Number],
      default: null,
    },
    /*
     * Force !important for style properties
     */
    important: {
      type: [Boolean, Array],
      default: false,
    },
  },
  data() {
    return {
      // works when content height becomes more then value of the maxHeight property
      maxHeightScroll: false,
      height: 'auto',
    };
  },
  computed: {
    computedStyles() {
      if (!this.autosize) return {};
      return {
        resize: !this.isResizeImportant ? 'none' : 'none !important',
        height: this.height,
        overflow: this.maxHeightScroll
          ? 'auto'
          : !this.isOverflowImportant
          ? 'hidden'
          : 'hidden !important',
      };
    },
    isResizeImportant() {
      const imp = this.important;
      return imp === true || (Array.isArray(imp) && imp.includes('resize'));
    },
    isOverflowImportant() {
      const imp = this.important;
      return imp === true || (Array.isArray(imp) && imp.includes('overflow'));
    },
    isHeightImportant() {
      const imp = this.important;
      return imp === true || (Array.isArray(imp) && imp.includes('height'));
    },
    val: {
      get() {
        return this.modelValue;
      },
      set(value) {
        this.$emit('update:modelValue', value);
        this.$nextTick(this.resize);
      },
    },
    value(val) {
      this.val = val;
      this.$nextTick(this.resize);
    },
  },
  watch: {
    autosize(val) {
      if (val) this.resize();
    },
  },
  methods: {
    resize() {
      const important = this.isHeightImportant ? 'important' : '';
      this.height = `auto${important ? ' !important' : ''}`;
      this.$nextTick(() => {
        let contentHeight = this.$refs.textarea.scrollHeight + 1;

        if (this.minHeight) {
          contentHeight =
            contentHeight < this.minHeight ? this.minHeight : contentHeight;
        }

        if (this.maxHeight) {
          if (contentHeight > this.maxHeight) {
            contentHeight = this.maxHeight;
            this.maxHeightScroll = true;
          } else {
            this.maxHeightScroll = false;
          }
        }

        const heightVal = contentHeight + 'px';
        this.height = `${heightVal}${important ? ' !important' : ''}`;
      });

      return this;
    },
  },
  mounted() {
    this.resize();
  },
};
</script>
