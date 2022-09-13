<template>
  <div
    class="
      flex
      justify-center
      items-center
      align-center
      p-4
      py-8
      m-2
      my-8
      bg-white
      rounded-sm
    "
  >
    <h3 class="text-bold text-gray-600 text-center">
      {{ title }}<br />
      <p class="leading-8">{{ subtitle }}</p>
    </h3>
  </div>
</template>

<script>
export default {
  props: {
    title: String,
    subtitle: String,
  },
};
</script>
