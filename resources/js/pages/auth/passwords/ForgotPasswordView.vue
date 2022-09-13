<template>
  <div
    class="
      min-h-screen
      bg-gray-50
      flex flex-col
      justify-center
      py-12
      sm:px-6
      lg:px-8
    "
  >
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
      <img class="mx-auto h-12 w-auto" src="/logo.png" alt="Workflow" />
      <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
        Forgot Password
      </h2>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
      <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
        <form class="space-y-6" method="POST" @submit.prevent="submit">
          <div class="space-y-6 sm:space-y-5">
            <div class="text-sm underline">
              <router-link
                :to="{ name: 'login' }"
                class="font-medium text-rose-600 hover:text-rose-500"
              >
                Go back to Login
              </router-link>
            </div>
            <div class="sm:border-t sm:border-gray-200 sm:pt-5">
              <p class="mt-2 text-sm text-gray-500">
                Enter your email to get an email with reset password link.
              </p>
              <div class="mt-1 pt-2 sm:mt-0 sm:col-span-2">
                <input
                  id="email"
                  name="email"
                  type="email"
                  v-model="form.email"
                  autocomplete="email"
                  required=""
                  placeholder="Enter your email"
                  class="
                    appearance-none
                    block
                    w-full
                    px-3
                    py-2
                    border border-gray-300
                    rounded-md
                    shadow-sm
                    placeholder-gray-400
                    focus:outline-none
                    focus:ring-rose-500
                    focus:border-rose-500
                    sm:text-sm
                  "
                />
                <p class="mt-2 text-sm text-gray-500">
                  After submit check your email inbox.
                </p>
              </div>
            </div>
            <div class="mt-1 relative rounded-md shadow-sm">
              <div
                v-show="errors[1]"
                class="
                  absolute
                  inset-y-0
                  right-0
                  pr-3
                  flex
                  items-center
                  pointer-events-none
                "
              >
                <!-- Heroicon name: solid/exclamation-circle -->
                <svg
                  class="h-5 w-5 text-red-500"
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 20 20"
                  fill="currentColor"
                  aria-hidden="true"
                >
                  <path
                    fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                    clip-rule="evenodd"
                  />
                </svg>
              </div>
            </div>

            <p class="mt-2 text-sm text-red-600" id="email-error">
              {{ errors[1] }}
            </p>
          </div>

          <div class="flex items-center justify-between">
            <button
              type="submit"
              :disabled="!form.email"
              class="
                w-full
                flex
                justify-center
                py-2
                px-4
                border border-transparent
                rounded-md
                shadow-sm
                text-sm
                font-medium
                text-white
                bg-rose-600
                hover:bg-rose-700
                disabled:opacity-50
                focus:outline-none
                focus:ring-2 focus:ring-offset-2 focus:ring-rose-500
              "
            >
              Reset Password
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';

export default {
  name: 'ForgotPasswordView',

  data() {
    return {
      form: {
        email: '',
      },
    };
  },

  computed: {
    ...mapGetters({
      errors: 'auth/errors',
    }),
  },

  methods: {
    ...mapActions({
      forgotPassword: 'auth/forgotPassword',
    }),

    async submit() {
      await this.forgotPassword(this.form);

      this.$router.push({ name: 'login' });

      this.$notify({
        group: "app",
        type: "success",
        title: "Done! Email sent!",
        text: "Reset password link sent on your email."
      }, 3000);
    },
  },
};
</script>

<style scoped>
</style>
