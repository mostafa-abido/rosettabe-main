import messaging from '@react-native-firebase/messaging';


export default function BackgroundMessageService() {
  console.log('background messaging subscription active');
  messaging().setBackgroundMessageHandler(async remoteMessage => {
    console.log('Message handled in the background!', remoteMessage);
  });
}
