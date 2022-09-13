<?php

namespace App\Notifications\Chat;

use Illuminate\Bus\Queueable;
use NotificationChannels\PusherPushNotifications\PusherChannel;
use NotificationChannels\PusherPushNotifications\PusherMessage;
use Illuminate\Notifications\Notification;

class ChatNoteCreatedNotification extends Notification
{
  use Queueable;

  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct($note)
  {
    $this->note = $note;
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
    $message = 'You have a new note in one of your chat';
    return PusherMessage::create()
      ->iOS()
      ->badge(1)
      ->title("Note:{$this->note->name}")
      ->body($message)
      ->withAndroid(
        PusherMessage::create()
          ->title("Note:{$this->note->name}")
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
      'message' => 'You have a new note in one of your chat',
      'data' => $this->note,
      'type' => 'note'
    ];
  }
}
