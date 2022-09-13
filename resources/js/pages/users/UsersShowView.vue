<template>
  <div class="w-full">
    <flex-title
      v-if="user.name"
      :title="`${user.name} Profile`"
      justify="center"
    />

    <div class="
        bg-white
        relative
        px-6
        py-5
        flex
        items-center
        space-x-3
        rounded-md
      ">
      <user-card-component v-if="user && (user.id == $route.params.id)" :user="user"/>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import { watch } from 'vue'

import UserCardComponent from "../../components/users/UserCardComponent";
import FlexTitle from '../../components/layout/menus/FlexTitleComponent';

export default {
    name: "UsersShowView",
    components: {
      UserCardComponent,
      FlexTitle
    },
    computed: {
        ...mapGetters({
            user: 'users/user',
        }),
    },

    methods: {
        ...mapActions({
            getUser: 'users/getUser',
        })
    },

    mounted () {

      this.getUser(this.$route.params.id);

      watch(
        () => this.$route?.params?.id,
        async newId => {
          if (newId) {
            await this.getUser(newId)
          }
        }
      )
    },
}
</script>

<style scoped>

</style>
