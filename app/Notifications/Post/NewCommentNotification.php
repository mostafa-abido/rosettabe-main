<?php

namespace App\Notifications\Post;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\PusherPushNotifications\PusherChannel;
use NotificationChannels\PusherPushNotifications\PusherMessage;

class NewCommentNotification extends Notification
{
  use Queueable;

  protected $comment;

  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct($comment)
  {
    $this->comment = $comment;
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
    $message = "You got new comment to your post";
    return PusherMessage::create()
      ->iOS()
      ->badge(1)
      ->title("Post:{$this->comment->post->title}")
      ->body($message)
      ->withAndroid(
        PusherMessage::create()
          ->title("Post:{$this->comment->post->title}")
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
      'message' => 'You got new comment to your post',
      'data' => $this->comment,
      'type' => 'comment',
    ];
  }
}
