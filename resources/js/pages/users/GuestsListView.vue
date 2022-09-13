<template>
  <div class="w-full">
    <flex-title :title="title" justify="center" :noBack="true" />

    <div class="flex space-x-5 items-center justify-center">
        <div v-for="home in homes.data" :key="home.id"
             :class="`w-10 py-2 text-center font-bold text-white cursor-pointer ${homesClasses[home.id - 1].class}`"
             @click="filteredGuests(home.id)"
        >
          {{ home.alias }}
        </div>
    </div>

    <ul v-if="guestFiltered?.length" class="grid grid-cols-2 gap-4 pt-4 pb-10">
      <li v-for="guest in guestFiltered" :key="guest.id">
        <router-link :to="{ name:'guest', params: { id: guest.id }}">
          <div class="bg-white rounded-md border-2 h-40">
          <div class="flex flex-col justify-center align-center">
            <div class="flex justify-center align-center py-4">
              <img :src="guest.photo1" class="bg-gray-600 rounded-full w-20 h-20 border-2 overflow-hidden max-w-full" />
            </div>
            <span class="text-center">{{ guest.alias }}</span>
          </div>
          </div>
        </router-link>
      </li>
    </ul>
    <template v-else-if="filter && !guestFiltered?.length">
      <div class="my-8">
        <div class="text-center text-xs text-gray-400">
          <p>No results</p>
        </div>
      </div>
    </template>
    <template v-else>
      <div class="my-8">
        <div class="text-center text-xs text-gray-400">
          <p>Loading...</p>
        </div>
      </div>
    </template>
  </div>
</template>
<script>
import FlexTitle from '../../components/layout/menus/FlexTitleComponent';
import { GUESTS } from '../../constants/pages';
import { mapGetters, mapState, mapActions } from 'vuex';
export default {
  name: 'GuestsListView',
  components: {
    FlexTitle,
  },
  data: () => ({
    loading: true,
    searchTerm: '',
    title: GUESTS,
    homesClasses: [],
    guestFiltered: [],
    filter: false,
    selectedHomeId: 0,
    tmpId: 0
  }),
  computed: {
    ...mapState('guests', ['guests', 'next']),
    ...mapState('homes', ['homes']),

    // TODO:implement after correct requirements

  },
  methods: {
    ...mapActions({
      getGuests: 'guests/getGuests',
      loadMore: 'guests/loadMoreGuests',
      getHomes: 'homes/getHomes'
    }),

    filteredGuests(id) {
      this.filter = true
      this.guestFiltered = []
      let guests = this.guests.data.filter(x => x.status === 'ACTIVE')

      if (this.selectedHomeId === id) {
        this.selectedHomeId = 0;
        this.guestFiltered = guests;
        return;
      }

      this.selectedHomeId = id;
      this.guestFiltered = guests.filter(guest => guest.home === id);
    },

    removeFilter() {
      this.filter = false
      this.guestFiltered = []
      this.guestFiltered = this.guests.data
    },

    async loadNext() {
      if (this.next && !this.loading) {
        this.setLoading(true)
        await this.loadMore(this.next)
        this.setLoading(false)
      }
    },
    setLoading(status) {
      this.loading = status;
    }
  },
  beforeMount() {
    this.homesClasses = [
      {'class': 'bg-green-500'},
      {'class': 'bg-red-500'},
      {'class': 'bg-yellow-500'},
      {'class': 'bg-blue-500'},
      {'class': 'bg-purple-500'},
      {'class': 'bg-pink-500'},
    ]
  },

  async mounted() {
    this.guestFiltered = []
    await this.getGuests().finally(() => {
      this.guestFiltered = this.guests.data
    });
    await this.getHomes().finally(() => {
      this.setLoading(false)
    });

    let lastKnownScrollPosition = 0;
    let ticking = false;

    const onScroll = (scrollPos) => {
      if (scrollPos <= 0 && this.loading === false) {
        this.loadNext();
      }
    }

    this.onScrollView = (e) => {
      const el = e.target;
      lastKnownScrollPosition = el.scrollHeight - el.scrollTop - el.offsetHeight;
      if (!ticking) {
        window.requestAnimationFrame(function() {
          onScroll(lastKnownScrollPosition);
          ticking = false;
        });
        ticking = true;
      }
    }

    document.getElementById('app').children[0].addEventListener('scroll', this.onScrollView, { capture: false, passive: true });
    document.getElementById('main-view').addEventListener('scroll', this.onScrollView, { capture: false, passive: true });
  },
  unmounted() {
    document.getElementById('app').children[0].removeEventListener('scroll', this.onScrollView);
    document.getElementById('main-view').removeEventListener('scroll', this.onScrollView);
  }
};
</script>
<style scoped></style>
