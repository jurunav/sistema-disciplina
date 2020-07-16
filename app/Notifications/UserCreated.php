<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserCreated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $user
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($user)
    {
        $mailMessage = (new MailMessage);
        $mailMessage->subject('Welcome to Vet Images');
        $mailMessage->greeting("Hello ". $user->name);
        $mailMessage->line("We are glad to inform you that you are now part of our team. Here are your credentials");
        $mailMessage->line('<strong>Username:</strong> '. $user->name );
        $mailMessage->line('<strong>Password:</strong>'. $user->tmp_password);
        $mailMessage->action('Login the app', url('/'));
        $mailMessage->line('Thank you for using our application!');
        return $mailMessage;
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
            //
        ];
    }
}
