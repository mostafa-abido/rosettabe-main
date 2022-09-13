import firebase from 'react-native-firebase'

export default function logMessage(message, ...args) {
  console.log(message, ...args)

  firebase.crashlytics().log(message)
}
