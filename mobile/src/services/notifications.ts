import notifee from '@notifee/react-native';

import messaging, { FirebaseMessagingTypes } from '@react-native-firebase/messaging';
import RNPusherPushNotifications from "react-native-pusher-push-notifications";

function subscribeToAndroidNotificationChannels() {
  notifee.createChannel({
    id: 'test',
    name: 'Default Channel',
  });

  notifee.createChannel({
    id: 'test_firebase',
    name: 'Default Channel',
  });

  notifee.createChannel({
    id: 'default',
    name: 'Default Channel',
  });
}

function initFirebaseMessaging() {
  async function onMessageReceived(message) {
    // Do something
    console.log(message);

    notifee.displayNotification({
      title: "Test Notification",
      body: "Test Notification Body",
      android: {
        channelId: "test_firebase"
      }
    });

  }

  messaging().onMessage(onMessageReceived);
  messaging().setBackgroundMessageHandler(onMessageReceived);
}

function initPusherMessaging() {
  const onSubscriptionsChanged = (interests: string[]): void => {
      console.log("CALLBACK: onSubscriptionsChanged");
      console.log(interests);
  }

  RNPusherPushNotifications.setInstanceId("43a912c9-158e-4150-ba74-07b7814466e4");
  RNPusherPushNotifications.setOnSubscriptionsChangedListener(onSubscriptionsChanged);

}

export function displayNotification (remoteMessage: FirebaseMessagingTypes.RemoteMessage ) {
  notifee.displayNotification({
    title: remoteMessage.notification.title,
    body: remoteMessage.notification.body,
    android: {
      channelId: 'default',
      color: '#f0505c',
      smallIcon: 'ic_stat_default', // optional, defaults to 'ic_launcher'.
    },
  });
}

export default function NotificationsService() {
  subscribeToAndroidNotificationChannels();
  initPusherMessaging();
}
