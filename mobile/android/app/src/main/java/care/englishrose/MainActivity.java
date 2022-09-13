package care.englishrose;
import android.content.res.Configuration;
import android.content.Intent;

import android.os.Bundle;

import com.facebook.react.ReactActivity;
import com.facebook.react.ReactActivityDelegate;
import com.facebook.react.ReactInstanceManager;
import com.facebook.react.ReactRootView;
import com.swmansion.gesturehandler.react.RNGestureHandlerEnabledRootView;
import expo.modules.ReactActivityDelegateWrapper;
import com.b8ne.RNPusherPushNotifications.NotificationsMessagingService;


public class MainActivity extends ReactActivity {

  protected void onStart() {
    super.onStart();

    ReactInstanceManager reactInstanceManager = getReactNativeHost().getReactInstanceManager();
    NotificationsMessagingService.read(reactInstanceManager, this);
  }

  // Added automatically by Expo Config
  @Override
  public void onConfigurationChanged(Configuration newConfig) {
      super.onConfigurationChanged(newConfig);
      Intent intent = new Intent("onConfigurationChanged");
      intent.putExtra("newConfig", newConfig);
      sendBroadcast(intent);
  }

  @Override
  protected void onCreate(Bundle savedInstanceState) {
    // Set the theme to AppTheme BEFORE onCreate to support
    // coloring the background, status bar, and navigation bar.
    // This is required for expo-splash-screen.
    setTheme(R.style.AppTheme);

    super.onCreate(null);
  }

  @Override
  public void onNewIntent(Intent intent) {
    super.onNewIntent(intent);
    setIntent(intent);
    ReactInstanceManager reactInstanceManager = getReactNativeHost().getReactInstanceManager();
    NotificationsMessagingService.read(reactInstanceManager, this);
  }

  /**
   * Returns the name of the main component registered from JavaScript.
   * This is used to schedule rendering of the component.
   */
  @Override
  protected String getMainComponentName() {
    return "main";
  }

  @Override
  protected ReactActivityDelegate createReactActivityDelegate() {
    return new ReactActivityDelegateWrapper(
      this,
      new ReactActivityDelegate(this, getMainComponentName()) {
      @Override
      protected ReactRootView createRootView() {
        return new RNGestureHandlerEnabledRootView(MainActivity.this);
      }
    });
  }
}
