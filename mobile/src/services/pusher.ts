import RNPusherPushNotifications from "react-native-pusher-push-notifications";

export const subscribe = (interest: string): void => {
    console.log(`Subscribing to "${interest}"`);

    RNPusherPushNotifications.subscribe(
        interest,
        (statusCode, response) => {
            console.error("[pusher:subscribe]: Status", statusCode, response);
        },
        () => {
            console.log(`[pusher:subscribeCallback]: Subscribed to ${interest}`);
        }
    );
  };

export const unsubscribe = (interest: string): void => {
    console.log(`Unsubscribing from "${interest}"`);

    RNPusherPushNotifications.unsubscribe(
        interest,
        (statusCode, response) => {
            console.error("[pusher:unsubscribe]: Status", statusCode, response);
        },
        () => {
            console.log(`[pusher:unsubscribeCallback]: Unsubscribed from ${interest}`);
        }
    );
  };
