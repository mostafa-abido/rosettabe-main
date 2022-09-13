<template>
  <div class="w-full">
    <flex-title
      :title="title"
      :to="{ path: '/app/posts/create' }"
      btnText="Create Post"
      justify="center"
      :noBack="true"
    />
    <div v-if="posts?.length > 0" class="mb-8">
      <pull-refresh v-model="loading" @refresh="onRefresh" successText="Done" pullingText="Swipe down to refresh" loosingText="Release to refresh" loadingText="Loading...">
        <ul class="space-y-4">
          <li v-for="post in posts" :key="post.id">
            <post-card-component :post="post" />
          </li>
        </ul>
      </pull-refresh>
      <div class="space-y-4 pb-4 relative">
        <div class="absolute inset-0 flex items-center pb-4" aria-hidden="true">
          <div class="w-full border-t border-gray-300" />
        </div>
      </div>
    </div>
    <template v-else-if="loading">
      <div class="my-8">
        <div class="text-center text-xs text-gray-400">
          <p>Loading...</p>
        </div>
      </div>
    </template>
    <empty-section
      v-else
      title="There are no posts in the feed yet"
      subtitle="Be the first who will create one."
    />
  </div>
</template>

<script>
import { mapState, mapActions } from 'vuex';
import PullRefresh from 'pull-refresh-vue3'
import EmptySection from '../../components/emptySection/EmptySection';
import FlexTitle from '../../components/layout/menus/FlexTitleComponent';
import PostCardComponent from '../../components/posts/PostCardComponent';
import { FEED } from '../../constants/pages';
export default {
  name: 'ListPostsView',
  components: { PullRefresh, PostCardComponent, FlexTitle, EmptySection },

  computed: {
    ...mapState('posts', ['posts', 'next']),
    ...mapState('auth', ['user']),
  },
  data: () => ({ loading: true, title: FEED }),
  methods: {
    ...mapActions({
      loadMore: 'posts/loadMore',
      getPosts: 'posts/getPosts',
    }),
    onRefresh() {
      this.getPosts().finally(() => this.setLoading(false));
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
  mounted() {
    this.getPosts().finally(() => this.setLoading(false));
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
