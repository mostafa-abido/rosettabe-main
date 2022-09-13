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
        Reset password
      </h2>
    </div>

    {{ JSON.stringify(errors, null, 4) }}
    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
      <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
        <form class="space-y-4" method="POST" @submit.prevent="submit">
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">
              Email
            </label>
            <div class="t-1 relative rounded-md shadow-sm">
              <input
                id="email"
                name="email"
                v-model="form.email"
                type="text"
                autocomplete="off"
                required=""
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
              <div
                v-show="errors?.email?.[0]"
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

            <p class="mt-2 text-sm text-red-600" id="password-error">
              {{ errors?.email?.[0] }}
            </p>
          </div>

          <div>
            <label
              for="password"
              class="block text-sm font-medium text-gray-700"
            >
              Password
            </label>
            <div class="t-1 relative rounded-md shadow-sm">
              <input
                id="password"
                name="password"
                v-model="form.password"
                type="password"
                autocomplete="off"
                required=""
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
              <div
                v-show="errors?.password?.[0]"
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

            <p class="mt-2 text-sm text-red-600" id="password-error">
              {{ errors?.password?.[0] }}
            </p>
          </div>

          <div>
            <label
              for="password"
              class="block text-sm font-medium text-gray-700"
            >
              Confirm new password
            </label>
            <div class="t-1 relative rounded-md shadow-sm">
              <input
                id="password_confirmation"
                name="password_confirmation"
                v-model="form.password_confirmation"
                type="password"
                autocomplete="off"
                required=""
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
            </div>
          </div>

          <div>
            <button
              type="submit"
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
                focus:outline-none
                focus:ring-2 focus:ring-offset-2 focus:ring-rose-500
              "
            >
              Reset
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  data() {
    return {
      form: {
        email: '',
        token: this.$route.params.token,
        password: '',
        password_confirmation: '',
      },
    };
  },

  computed: {
    ...mapGetters({
      user: 'auth/user',
      errors: 'auth/errors',
    }),
  },

  methods: {
    ...mapActions({
      restorePassword: 'auth/restorePassword',
    }),

    async submit() {
      const res = await this.restorePassword(this.form);

      if (!res.errors) {
        // TODO add confirmation toast
        await this.$router.replace({ name: 'login' });
      }
    },
  },
};
</script>

<style scoped>
</style>
