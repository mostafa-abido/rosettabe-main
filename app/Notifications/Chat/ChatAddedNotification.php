<?php

namespace App\Notifications\Chat;

use Illuminate\Bus\Queueable;
use NotificationChannels\PusherPushNotifications\PusherChannel;
use NotificationChannels\PusherPushNotifications\PusherMessage;
use Illuminate\Notifications\Notification;

class ChatAddedNotification extends Notification
{
  use Queueable;

  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct($chat)
  {
    $this->chat = $chat;
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
    $message = 'You have been added to chat';
    return PusherMessage::create()
      ->iOS()
      ->badge(1)
      ->title("Chat:{$this->chat->name}")
      ->body($message)
      ->withAndroid(
        PusherMessage::create()
          ->title("Chat:{$this->chat->name}")
          ->body($message)
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
      'message' => 'You have been added to chat',
      'data' => $this->chat,
      'type' => 'chat'
    ];
  }
}
