<template>
  <div
    class="min-h-screen bg-gray-50 p-4">
    <div
      class="
        min-h-screen
        flex flex-col
        justify-center
        sm:px-6
        lg:px-8
      "
    >
      <div class="sm:mx-auto sm:w-50 sm:max-w-md py-4 my-2">
        <img class="mx-auto h-12 w-auto" src="/logo.png" alt="Workflow" />
      </div>

      <div class="sm:mx-auto sm:w-full sm:max-w-md bg-white px-4 py-8 shadow sm:rounded-lg sm:px-10">
        <form class="space-y-2" method="POST" @submit.prevent="submit">
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">
              Email address
            </label>
            <div class="mt-1 relative rounded-md shadow-sm">
              <input
                id="email"
                name="email"
                type="email"
                v-model="form.email"
                autocomplete="email"
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

          <div>
            <label
              for="password"
              class="block text-sm font-medium text-gray-700"
            >
              Password
            </label>
            <div class="mt-1 relative rounded-md shadow-sm">
              <input
                id="password"
                name="password"
                v-model="form.password"
                type="password"
                autocomplete="current-password"
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
                v-show="errors[2]"
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
              {{ errors[2] }}
            </p>
          </div>

          <div>
            <div class="pb-6 flex items-center justify-between">
              <div class="w-60 flex items-center">
                <input
                  id="remember_me"
                  name="remember_me"
                  type="checkbox"
                  class="
                    h-4
                    w-4
                    text-rose-600
                    focus:ring-rose-500
                    border-gray-300
                    rounded
                  "
                />
                <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                  Remember me
                </label>
              </div>

              <div class="w-full text-right text-sm">
                <router-link
                  :to="{ name: 'forgot-password' }"
                  class="font-medium text-rose-600 hover:text-rose-500"
                >
                  Forgot your password?
                </router-link>
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
                Sign in
              </button>
            </div>

            <div v-if="isNativeApp()" class="py-2">
              <a href="/app/policy" target="_blank" class="font-medium text-rose-400 hover:text-rose-600 text-xs">
                App Privacy Policy
              </a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  name: 'SignIn',

  data() {
    return {
      form: {
        email: '',
        password: '',
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
      getUser: 'auth/getUser',
      signIn: 'auth/signIn',
    }),
    isNativeApp() {
      return !!window.isNativeApp
    },
    async submit() {
      await this.signIn(this.form);
      await this.getUser();
      const {
        data: { is },
      } = this.user;

      if (this.user && localStorage.getItem('access_token')) {
        this.$router.push({ name: is === 2 ? 'chats' : 'feed' });
      }
    },
  },
};
</script>

<style scoped>
</style>
