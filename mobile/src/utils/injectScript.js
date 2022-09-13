export default function injectMessage(webView, fn, eventName ) {
  if(fn) {
    setTimeout(() => webView.injectJavaScript(`(function(){
        var fn = ${fn.toString()}

        fn((data) => {
          window.ReactNativeWebView.postMessage( JSON.stringify({eventName: '${eventName}', data: data }))
        })
      })()
    `), 100)
  }
}
