<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Config;

class CustomResetPassword extends ResetPassword
{
    use Queueable;

    public function __construct($token)
    {
        $this->token = $token;
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
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->from(Config::get('mail.from.address'), Config::get('mail.from.name'))
                    ->subject('【東京肉NS】パスワードリセット')
                    ->greeting('東京肉NSです！')
                    ->line('下のボタンをクリックしてパスワードを再設定してください。')
                    ->action('パスワードリセット', url(route('password.reset', $this->token, false)))
                    ->line('このメールの内容に身に覚えがない場合は、このまま破棄してください。')
                    ->salutation('Enjoy 肉 life!');
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
