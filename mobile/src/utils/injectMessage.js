export default function injectMessage(webView, data) {
  setTimeout(() => webView.injectJavaScript('(function(){ window.ReactNativeWebView.postMessage(JSON.stringify('+ JSON.stringify(data) +')) })()'), 1000)
}
