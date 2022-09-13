import messaging, { FirebaseMessagingTypes } from '@react-native-firebase/messaging';
import AsyncStorage from '@react-native-async-storage/async-storage';
function onMessageHandler() {

}

function onNotificationOpenedAppHandler(remoteMessage) {
  console.log(
    'Notification caused app to open from background state:',
    remoteMessage.notification,
  );
  AsyncStorage.setItem('defaultPath', '/app/notifications')
}

async function checkForInitialNotification() {
  const initialMessage = await messaging()
      .getInitialNotification()

  if (initialMessage) {
    console.log(
      'Notification caused app to open from quit state:',
      initialMessage,
    );
    AsyncStorage.setItem('defaultPath', '/app/notifications')
  }

}

export const RNFirebaseNotificationHandlerEffect = (handler: (remoteMessage: FirebaseMessagingTypes.RemoteMessage) => void ) => {
  console.log('RNFirebaseNotificationHandlerEffect: init')

  const unsubscribe = messaging().onMessage(async remoteMessage => {
    console.log('[RNFirebaseNotificationHandlerEffect]: onMessage', remoteMessage);
    handler(remoteMessage);
  });

  // Assume a message-notification contains a "type" property in the data payload of the screen to open
  messaging().onNotificationOpenedApp(onNotificationOpenedAppHandler);

  // Check whether an initial notification is available
  checkForInitialNotification();

  return () => unsubscribe();
}
