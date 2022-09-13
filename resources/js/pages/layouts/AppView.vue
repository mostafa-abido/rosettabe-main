<template>
  <div class="overflow-y-scroll lg:overflow-visible select-none" :style="isUser ? 'max-height: 100vh;' : ''">
  <header-component />
  <div class="my-0 mx-auto mb-6 mt-0 lg:overflow-y-scroll" id="main-view">
    <div
      class="
        mx-auto
        flex flex-col
        lg:max-w-7xl
        lg:px-8
        lg:grid lg:grid-cols-12
        lg:gap-8
      "
    >
      <aside class="hidden lg:block lg:col-span-3 xl:col-span-2">
        <nav-component />
      </aside>
      <main class="lg:col-span-9 xl:col-span-6 main-content-container">
        <div class="lg:sticky top-0 mt-6 lg:mt-0 relative lg:pt-2 min-h-full flex flex-1 h-full">
          <template v-if="user">
            <router-view />
          </template>
        </div>
      </main>
    </div>
  </div>
  <bottom-nav-component v-if="!isUser"/>
  <toast-component />
  </div>
  <add-to-home-screen-component />
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import HeaderComponent from '../../components/layout/HeaderComponent';
import NavComponent from '../../components/layout/NavComponent';
import BottomNavComponent from '../../components/layout/BottomNavComponent';
import ToastComponent from '../../components/Toast/ToastComponent';
import AddToHomeScreenComponent from '../../components/dialogs/AddToHomeScreenComponent';

export default {
  components: {
    NavComponent,
    HeaderComponent,
    BottomNavComponent,
    ToastComponent,
    AddToHomeScreenComponent
  },
  computed: {
    ...mapGetters({
      user: 'auth/user',
    }),
    isUser() {
      return this.user.data.is === 2
    }
  },
  methods: {
    ...mapActions({
      getUser: 'auth/getUser',
    })
  },
  async mounted() {
    await this.getUser();
  }
};
</script>

<style>
html {
  overscroll-behavior: none;
  min-height: 100vh;
  overflow-y: hidden;
}

body {
  position: absolute;
  top: 0;
  bottom: 0;
  width: 100%;
}

#main-view {
  max-height: calc(100vh - 56px);
  min-height: calc(100vh - 56px);
}

#app > div {
  max-height: calc(-60px + 100vh);
}

@media screen and (max-width: 768px) {
  #main-view {
    min-height: calc(100vh - 64px);
  }
  #app {
    width: 100%;
    top: 0;
    bottom: 0;
    position: absolute;
  }

  #app > div {
    max-height: calc(-44px + 100vh);
  }
}

.linkified {
  text-decoration: underline !important;
}

.main-content-container {
  padding: 0 8px;
  min-height: calc(100vh - 56px);
}

.main-content-container > * {
  min-height: fit-content;
}
</style>
