<template>
  <div class="w-full">
    <flex-title-component title="New Notifications" :noBack="true"/>
    <ul v-if="allNotifications.length > 0" class="divide-y divide-gray-200">
      <li
        v-for="(notification) in allNotifications"
        :key="notification.id"
        class="px-4 py-4"
      >
        <div class="flex">
          <div class="flex-1">
            <router-link :to="getNotificationRedirect(notification)" class="text-sm text-gray-500 flex items-center">
              <b class="w-20 block text-center mr-4"><time :datetime="notification.updated_at">{{
                formatDate(notification.updated_at)
              }}</time></b>
              <p class="pr-2">{{getNotificationMessage(notification)}}</p>
              </router-link>
          </div>
        </div>
      </li>
    </ul>
    <div v-else>
      <div v-if="notificationsLoaded" class="flex justify-center items-center py-8 mt-8 bg-white rounded-lg">
        <h3 class="text-sm font-medium text-gray-500">No New Notifications</h3>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import UserAvatar from '../../../components/avatar/UserAvatar';
import FlexTitleComponent from '../../../components/layout/menus/FlexTitleComponent';

export default {
  components: { UserAvatar, FlexTitleComponent },
  name: 'UserNotificationsView',
  data() {
    return {
      notificationsLoaded: false,
      allNotifications: [],
      nextPageCursor: null
    }
  },
  computed: {
    ...mapGetters({
      user: 'auth/user'
    })
  },
  methods: {
    ...mapActions({
      getUser: 'auth/getUser',
    }),
    formatDate(date) {
      return new Intl.DateTimeFormat('en-US', {
        year: 'numeric',
        month: 'numeric',
        day: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
      }).format(new Date(date));
    },
    getNotificationRedirect(notification) {
      switch (notification.data.type) {
        case 'chat':
          return { name: 'chats' }
        case 'message':
          return { name: 'chats' }
        case 'note':
          return { name: 'chats' }
        case 'post':
          return { name: 'feed' }
        case 'comment':
          return { name: 'feed' }
        default:
          return { name: 'feed' }
      }
    },
    getNotificationMessage(notification) {
      switch(notification?.data?.type) {
        case "post":
          if (notification.type === 'App\\Notifications\\Post\\PostSubmittedNotification') {
            return `${notification?.data?.message}: - "${notification?.data?.title}"`;
          }
          if (notification?.data?.post?.author_id == notification?.data?.user_id) {
            return `Your Post "${notification?.data?.title || ''}" has been approved.`
          } else {
            return notification?.data?.message || 'Some Unknown Post event'
          }
        case "message":
          return `Chat: ${notification?.data?.data?.chat?.name} – new messages.`
        case "note":
          const note = notification?.data?.data;
          return `Note: ${note?.name} – note ${note?.updated_at ? "updated" : "created"}.`
        case "comment":
          return `Post: ${notification?.data?.data?.post?.title} - ${notification?.data?.message}`
        case "chat":
          if (notification?.data?.data?.chat?.id) {
            return `New Chat: "${notification?.data?.data?.name}"`
          } else {
            return notification?.data?.message
          }
        default:
          return "..."
      }
    }
  },
  async mounted() {
    try {
      await this.getUser();
      const {data: { data, next_page_url}} = await axios.get('/api/notifications')

      const allNotifications = data
      const nextPageCursor = next_page_url

      this.notificationsLoaded = true

      // Handle notifications to display only unique events, filter out read notifications.

      this.allNotifications = allNotifications.filter(({ read_at }) => read_at === null)

      if (nextPageCursor) {
        this.nextPageCursor = nextPageCursor
      } else {
        this.nextPageCursor = null
      }
    } catch (e) {
      this.notificationsLoaded = true
      console.log(e)
    }
  }
};
</script>

<style scoped>
</style>
