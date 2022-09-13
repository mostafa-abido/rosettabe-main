import firebase from 'react-native-firebase'

export default function logError(error, data) {
  if (__DEV__) {
    console.log(error, data)
  } else {
    console.log(error, data)
    firebase.crashlytics().recordError(error.code, error.message)
  }
}
