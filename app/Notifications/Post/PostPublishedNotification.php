<?php

namespace App\Notifications\Post;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\PusherPushNotifications\PusherChannel;
use NotificationChannels\PusherPushNotifications\PusherMessage;

class PostPublishedNotification extends Notification
{
  use Queueable;

  protected $post;

  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct($post)
  {
    $this->post = $post;
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
    $message = 'Your post has been published';
    return PusherMessage::create()
      ->iOS()
      ->badge(1)
      ->title("Post:{$this->post->title}")
      ->body($message)
      ->withAndroid(
        PusherMessage::create()
          ->title("Post:{$this->post->title}")
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
      'message' => 'Your post has been published',
      'title' => $this->post->title,
      'user_id' => $this->post->user->id,
      'post' => $this->post,
      'type' => 'post'
    ];
  }
}
