
import AsyncStorage from '@react-native-async-storage/async-storage';
import React, { Ref, useCallback, useEffect, useState } from 'react';
import { ImageBackground, Platform, StyleSheet, View } from 'react-native';

import { WebView as WebViewComponent } from 'react-native-webview';

import { subscribe, unsubscribe } from '../../services/pusher';

import { useBackHandler } from '@react-native-community/hooks'

const injectedJavaScriptBeforeContentLoaded = `
  (function(){
    window.isNativeApp = true;

    var userId = null;

    function logPostMessage(data) {
      var data = {
        type: 'log',
        data: data
      };

      window.ReactNativeWebView.postMessage(JSON.stringify(data));
      console.log(data.message, data);
    }

    logPostMessage({ message: 'started script', data: { location: window.location.href }});

    function checkLocalStorage () {
      if (localStorage.userData) {
        try {
          var data = JSON.parse(localStorage.userData);
          if (userId !== data.data.id) {
            userId = data.data.id;
            data.type = 'userData';
            data.accessToken = localStorage['access_token'];
            window.ReactNativeWebView.postMessage(JSON.stringify(data));
          }
        } catch (e) {
          var data = {
            type: 'log',
            data: {
              message: 'failed to parse',
              userId: userId
            }
          }
          window.ReactNativeWebView.postMessage(JSON.stringify(data));
          console.log('failed to parse', e);
        }
      } else {
        if (userId) {
          var data = {
            type: 'userLogOut',
            userId: userId
          };

          window.ReactNativeWebView.postMessage(JSON.stringify(data));

          userId = null;
        }
      }
      setTimeout(checkLocalStorage, 1000)
    };

    setTimeout(checkLocalStorage, 1000);
  })();
  true; // note: this is required, or you'll sometimes get silent failures
`;

const whiteList = Platform.OS === 'android' ? ['https://englishrose.care'] : ['https://englishrose.care']
const uri = 'https://englishrose.care'

type webViewProps = {
  onLoad: (event: any) => void,
  onError: (err: any) => void
}

export const WebView: (webViewProps) => any = React.forwardRef(({ onLoad, onError }: webViewProps, webViewRef: React.ForwardedRef<WebViewComponent>) => {
  const [canGoBack, setCanGoBack] = useState(false)

  const onMessage = useCallback((event) => {
    const data: any = event?.nativeEvent?.data;
    if (data) {
      let parsedData = null;
      try {
        parsedData = JSON.parse(data);
      } catch (e) {
        console.log('failed to parse message data')
      }
      if (parsedData.type === 'userData') {
        console.log(parsedData.type, parsedData.accessToken, parsedData.data.id);

        if (parsedData.data.id) {
          subscribe(`user-${parsedData.data.id}`)
        }
      } else if(parsedData.type === 'userLogOut') {
        console.log(parsedData.type, parsedData.userId);
        unsubscribe(`user-${parsedData.userId}`)
      } else if(parsedData.type === 'log') {
        console.log('[webview log]:', parsedData.data.message, parsedData.data.data || parsedData);
      } else {
        console.log(parsedData)
      }
    }
  }, []);

  const [url, setURL] = useState<string>(null)

  const loadDefaultPath = useCallback(async () => {
    let defaultPath = '/app';
    try {
      const path = await AsyncStorage.getItem('defaultPath')
      if (path) {
        defaultPath = path
        await AsyncStorage.removeItem('defaultPath')
      }
    } catch (e) {
      console.log('failed to get default path', e)
    }
    setURL(`${uri}${defaultPath}`)
  }, [])

  useEffect(() => {
    loadDefaultPath()
    return () => {
      setURL(null)
    }
  }, [])

  useBackHandler(() => {
    if (canGoBack) {
      if(webViewRef?.current) {
        webViewRef.current.goBack()
        return true
      } else {
        return false
      }
    }
  })

  return url ? (
    <>
      <WebViewComponent
        ref={webViewRef}
        style={styles.webView}
        originWhitelist={whiteList}
        onMessage={onMessage}
        source={{ uri: url }}
        onHttpError={(syntheticEvent) => {
          const { nativeEvent } = syntheticEvent;
          console.warn(
            'WebView received error status code: ',
            nativeEvent.statusCode,
          );
          onError(nativeEvent)
        }}
        onNavigationStateChange={(navState) => {
          console.log('navState || ', navState)
          if (navState.url === "https://englishrose.care/app/feed") {
            setCanGoBack(false)
          } else {
            setCanGoBack(navState.canGoBack)
          }
        }}
        onShouldStartLoadWithRequest={(request) => {
          // Only allow navigating within this website
          return request.url.startsWith('https://englishrose.care/app');
        }}
        onLoad={(syntheticEvent) => {
          const { nativeEvent } = syntheticEvent

          if (nativeEvent.url === "https://englishrose.care/app/feed") {
            setCanGoBack(false)
          } else {
            setCanGoBack(nativeEvent.canGoBack)
          }
          onLoad(nativeEvent)
        }}
        bounces={false}
        allowsBackForwardNavigationGestures
        injectedJavaScriptBeforeContentLoaded={injectedJavaScriptBeforeContentLoaded}
        automaticallyAdjustContentInsets={false}
        contentInsetAdjustmentBehavior={"never"}
        automaticallyAdjustsScrollIndicatorInsets={false}
        limitsNavigationsToAppBoundDomains
        setBuiltInZoomControls={false}
        textZoom={100}
      />
    </>
  ): null
})

const styles = StyleSheet.create({
  webView: {
    flex: 0,
    height: '100%',
    backgroundColor: '#ECF0F1',
  }
});
