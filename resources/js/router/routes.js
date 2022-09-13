import LoginView from '../pages/auth/LoginView';
import AppView from '../pages/layouts/AppView';
import BaseView from '../pages/layouts/BaseView';
import PolicyView from '../pages/policy/PolicyView';
import ResourcesView from '../pages/resources/ResourcesView';
import CreatePostView from '../pages/posts/CreatePostView';
import UsersListView from '../pages/users/UsersListView';
import UsersShowView from '../pages/users/UsersShowView';
import GuestsListView from "../pages/users/GuestsListView";
import GuestsShowView from "../pages/users/GuestsShowView";
import ChatListView from '../pages/chats/ChatListView';
import ChatShowView from '../pages/chats/ChatShowView';
import ListPostsView from '../pages/posts/ListPostsView';
import UserNotificationsView from '../pages/auth/profile/UserNotificationsView';
import ListNotesView from '../pages/notes/ListNotesView';
import ForgotPasswordView from '../pages/auth/passwords/ForgotPasswordView';
import ResetView from '../pages/auth/passwords/ResetView';
import CreateNoteView from '../pages/notes/CreateNoteView';

const APP_PATH_PREFIX = '/app';
const ROUTES = {
  CHATS: 'chats',
  FEED: 'feed',
  NOTIFICATIONS: 'notifications',
};

const tryParseUserData = () => {
  const userData = localStorage.getItem('userData');
  if (userData) {
    try {
      const { data } = JSON.parse(userData);
      return data;
    } catch (e) {
      console.log('failed to parse userData from storage');
    }
  }
  return null;
};

const rootRedirect = (to) => {
  const userData = tryParseUserData();

  if (userData) {
    if (userData?.is === 2) {
      return `${APP_PATH_PREFIX}/${ROUTES.CHATS}`;
    }
  }
  return `${APP_PATH_PREFIX}/${ROUTES.FEED}`;
};

const routes = [
  {
    name: 'root',
    path: APP_PATH_PREFIX,
    component: AppView,
    redirect: rootRedirect,
    children: [
      {
        name: 'feed',
        path: ROUTES.FEED,
        component: ListPostsView,
        meta: {
          requiresAuth: true,
        },
      },

      {
        name: 'notifications',
        path: ROUTES.NOTIFICATIONS,
        component: UserNotificationsView,
        meta: {
          requiresAuth: true,
        },
      },

      {
        name: 'notes',
        path: 'notes/:id',
        component: ListNotesView,
        meta: {
          requiresAuth: true,
        },
      },

      {
        name: 'users',
        path: 'users',
        component: UsersListView,
        meta: {
          requiresAuth: true,
        },
      },

      {
        path: 'users/:id',
        name: 'user',
        component: UsersShowView,
        meta: {
          requiresAuth: true,
        },
      },
      {
        name: 'guests',
        path: 'guests',
        component: GuestsListView,
        meta: {
          requiresAuth: true,
        },
      },

      {
        path: 'guests/:id',
        name: 'guest',
        component: GuestsShowView,
        meta: {
          requiresAuth: true,
        },
      },

      {
        name: 'resources',
        path: 'resources',
        component: ResourcesView,
        meta: {
          requiresAuth: true,
        },
      },

      {
        path: 'posts/create',
        name: 'create-post',
        component: CreatePostView,
        meta: {
          requiresAuth: true,
        },
      },
      {
        name: 'chats',
        path: 'chats',
        component: ChatListView,
        meta: {
          requiresAuth: false,
        },
        props: true,
      },
      {
        path: 'chats/:chatId',
        name: 'chat',
        component: ChatShowView,
        meta: {
          requiresAuth: false,
        },
        props: true,
      },
      {
        name: 'notes-create',
        path: 'chats/:chatId/notes/create',
        component: CreateNoteView,
        meta: {
          requiresAuth: true,
        },
      },
      {
        name: 'notes-edit',
        path: 'chats/:chatId/notes/:id/edit',
        component: CreateNoteView,
        props: { action: 'edit' },
        meta: {
          requiresAuth: true,
        },
      },
      {
        name: 'notes-delete',
        path: 'chats/:chatId/notes/:id/delete',
        component: CreateNoteView,
        props: { action: 'delete' },
        meta: {
          requiresAuth: true,
        },
      },
    ],
  },

  {
    path: '/app/auth/',
    component: BaseView,
    children: [
      {
        name: 'login',
        path: '/app/auth/login',
        component: LoginView,
        meta: {
          requiresAuth: false,
        },
      },

      {
        name: 'forgot-password',
        path: '/app/auth/forgot-password',
        component: ForgotPasswordView,
        meta: {
          requiresAuth: false,
        },
      },

      {
        name: 'reset-password',
        path: '/app/auth/password/reset/:token',
        component: ResetView,
        meta: {
          requiresAuth: false,
        },
      },
    ],
  },

  {
    path: '/app/policy/',
    component: PolicyView,
  },
];

export default routes;
