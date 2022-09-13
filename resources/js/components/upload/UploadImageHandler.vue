<template>
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

    <div class="flex text-sm text-gray-600">
      <label
        for="file-upload"
        class="
          relative
          cursor-pointer
          bg-white
          rounded-md
          font-medium
          text-rose-600
          hover:text-rose-500
          focus-within:outline-none
          focus-within:ring-2
          focus-within:ring-offset-2
          focus-within:ring-rose-500
        "
      >
        <span>Press to Upload a file</span>
      </label>
        <input
          id="file-upload"
          name="file-upload"
          type="file"
          ref="file"
          multiple="multiple"
          class="sr-only"
          v-bind="inputBind()"
        />
      <!-- <p class="pl-1">or drag and drop</p> -->
    </div>

    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
    <p v-if="error" class="text-xs text-rose-500">{{ error }}</p>
</template>

<script>
  export default {
    props: {
      error: String,
      inputBind: Function,
    }
  }
</script>
