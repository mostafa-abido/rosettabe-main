<template>
  <div :class="[`h-${size}`, `w-${size}`, 'bg-black border border-inner shadow-md shadow-inner rounded-full overflow-hidden']">
    <img class="w-full h-full object-cover bg-gray-50" :src="src"/>
  </div>
</template>
<script>
export default {
  props: {
    size: {
      default: 10,
    },
    src: String,
    user: Object,
  },
};
</script>
