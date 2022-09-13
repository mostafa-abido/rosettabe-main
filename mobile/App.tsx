import React, { useCallback, useEffect, useRef, useState } from 'react';
import {
  Alert,
  BackHandler,
  Platform,
  SafeAreaView,
  StatusBar,
  StyleSheet,
  View,
} from 'react-native';
import * as SplashScreen from 'expo-splash-screen';

import NetInfo from '@react-native-community/netinfo';

import { WebView } from './src/components/webview/WebView';
import { RNFirebaseNotificationHandlerEffect } from './src/hooks/ notifications/RNFirebaseNotificationHandlerEffect';
import { OfflineModal } from './src/components/offline-modal/OfflineModal';
import * as NotificationsService from './src/services/notifications';

SplashScreen.preventAutoHideAsync()

const showNoInternetAlert = () => {
  if (Platform.OS === 'android') {
    Alert.alert(
      'No Internet Connection',
      'English Rose requires an active internet connection. Reconnect to the internet and try again. Application will exit now.',
      [
        {
          text: 'Ok',
          onPress: () => BackHandler.exitApp(),
        },
      ]
    );
  }
};

export default function App() {
  const [readyState, setReadyState] = useState(false);
  const [isConnectedStatus, setIsConnectedStatus] = useState(null);
  const [connectedType, setConnectedType] = useState(null);
  const [isWebViewLoaded, setWebViewLoaded] = useState(false);

  const webViewRef = useRef(null);

  const updateNetState = useCallback((state) => {
    console.log('Connection type', state.type);
    console.log('Is connected?', state.isConnected);

    setConnectedType(state.type);
    setIsConnectedStatus(state.isConnected);

    if (state.isConnected) {
      SplashScreen.hideAsync();
    }
  }, []);

  useEffect(() => {
    const unsubscribe = NetInfo.addEventListener(updateNetState);
    return () => unsubscribe();
  }, []);

  useEffect(() => {
    if (isWebViewLoaded && isConnectedStatus) {
      SplashScreen.hideAsync();
      setReadyState(true);
    } else {
      if (!isWebViewLoaded && isConnectedStatus === false) {
        showNoInternetAlert();
      } else {
        // !!! Do not reload if connection status: false but webview is loaded
        if(!isWebViewLoaded && webViewRef?.current) {
          webViewRef?.current?.reload();
        }
      }
    }
  }, [isWebViewLoaded, isConnectedStatus]);

  useEffect(
    () =>
      RNFirebaseNotificationHandlerEffect((remoteMessage) => {
        NotificationsService.displayNotification(remoteMessage);
      }),
    []
  );

  const forceCheckConnection = useCallback(() => {
    console.log('forceCheckConnection')
    NetInfo.fetch().then((state) => {
      console.log('forceCheckConnection', state)
      updateNetState(state);

      if (state.isConnected) {
        webViewRef.current.reload();
      }
    });
  }, []);

  const onLoad = useCallback((e) => {
    console.log('onLoad || ', e);

    if (e.loading === false) {
      setWebViewLoaded(true);
    }
  }, []);

  const onError = useCallback((e) => {
    console.log('onError ||', e)
  }, []);

  return (
    <SafeAreaView style={styles.safeAreaViewContainer}>
      <StatusBar
        barStyle={Platform.OS === 'ios' ? 'dark-content' : 'dark-content'}
        translucent={false}
      />

      <View style={{ flex: 1 }}>
        <WebView
          ref={webViewRef}
          onLoad={onLoad}
          onError={onError}
        />
      </View>

      <OfflineModal
        modalVisible={isConnectedStatus === false}
        onPress={forceCheckConnection}
      />
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safeAreaViewContainer: {
    flex: 0,
    height: '100%',
    backgroundColor: '#ECF0F1',
  },
  connectionStatusContainer: {
    paddingVertical: 8,
    backgroundColor: '#fff',
    alignItems: 'center',
    justifyContent: 'center',
  },
});
