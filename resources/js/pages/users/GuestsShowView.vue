<template>
   <div class="w-full">
    <flex-title
     v-if="guest.fname"
     :title="`${ guest.fname }Test Profile`"
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

       <guest-card-component v-if="guest && (guest.id == $route.params.id)" :user="guest"/>
    </div>
  </div>
</template>
<script>
import GuestCardComponent from "../../components/users/GuestCardComponent";
import FlexTitle from '../../components/layout/menus/FlexTitleComponent';
import {mapActions, mapGetters} from "vuex";
import { watch } from 'vue'

export default {
  name: "GuestsShowView",
  components: {
    FlexTitle,
    GuestCardComponent
  },
  computed: {
   ...mapGetters({
     guest: 'guests/guest'
   }),
 },

  methods: {
    ...mapActions({
      getGuest: 'guests/getGuest',
    })
  },

  mounted () {
    this.getGuest(this.$route.params.id);
    console.log(this.guest)
    console.log(this.$route.params.id)

    watch(
      () => this.$route?.params?.id,
      async newId => {
        if (newId) {
          await this.getGuest(newId)
        }
      }
    )
  },
}
</script>
<style scoped>
</style>
