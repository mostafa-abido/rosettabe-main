export default function (webView) {
  if (webView) {
    return (fnBody) => {
      webView.injectJavaScript(`(function() {
        var fn = ${fnBody};
        fn();
      })()`)
    }
  } else {
    console.warn('webView is not defined')
  }
}
