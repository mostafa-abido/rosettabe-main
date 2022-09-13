import { createStore } from 'vuex';
import { authModule } from './modules/auth';
import { postsModule } from './modules/posts';
import { chatModule } from './modules/chat';
import { requestModule } from './modules/request';
import { usersModule } from './modules/users';
import { resourceModule } from './modules/resource';
import { noteModule } from './modules/note';
import { guestsModule } from "./modules/guests";
import { homesModule } from "./modules/homes";

const store = createStore({
  modules: {
    note: noteModule,
    auth: authModule,
    posts: postsModule,
    chats: chatModule,
    requests: requestModule,
    users: usersModule,
    resources: resourceModule,
    guests: guestsModule,
    homes: homesModule
  },
});

export default store;
