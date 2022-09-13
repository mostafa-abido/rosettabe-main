<?php

namespace App\Notifications\Message;

use Illuminate\Bus\Queueable;
use NotificationChannels\PusherPushNotifications\PusherChannel;
use NotificationChannels\PusherPushNotifications\PusherMessage;
use Illuminate\Notifications\Notification;

class MessageCreatedNotification extends Notification
{
  use Queueable;

  protected $message;

  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct($message)
  {
    $this->message = $message;
  }

  /**
   * Get the notification's delivery channels.
   *
   * @param  mixed  $notifiable
   * @return array
   */
  public function via($notifiable)
  {
    return ['database', PusherChannel::class];
  }

  public function toPushNotification($notifiable)
  {
    $message = "New message in one of your chats!";
    return PusherMessage::create()
      ->iOS()
      ->badge(1)
      ->body($message)
      ->title("Chat:{$this->message->chat->name}")
      ->withAndroid(
        PusherMessage::create()
          ->title($message)
          ->icon('icon')
      );
  }

  /**
   * Get the array representation of the notification.
   *
   * @param  mixed  $notifiable
   * @return array
   */
  public function toArray($notifiable)
  {
    return [
      'message' => 'New message created',
      'data' => $this->message,
      'type' => 'message'
    ];
  }
}
