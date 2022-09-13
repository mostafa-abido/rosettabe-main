<?php

namespace App\Notifications\Post;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;

use Illuminate\Notifications\Notification;

class PostSubmittedNotification extends Notification
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
    return ['database', 'mail'];
  }

  /**
   * Get the mail representation of the notification.
   *
   * @param  mixed  $notifiable
   * @return \Illuminate\Notifications\Messages\MailMessage
   */
  public function toMail($notifiable)
  {
    return (new MailMessage)
      ->subject("New Post has been submitted")
      ->line('New Post has been submitted and waiting for approval.')
      ->action('Check Posts', url('/admin'));
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
      'message' => 'New Post has been submitted',
      'title' => $this->post->title,
      'user_id' => $this->post->user->id,
      'post' => $this->post,
      'type' => 'post'
    ];
  }
}
