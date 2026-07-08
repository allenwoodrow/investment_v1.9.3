<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WithdrawalApproved extends Notification
{
    use Queueable;
    public $name;
    public $amount;

    public function __construct($name, $amount)
    {
        $this->name = $name;
        $this->amount = $amount;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Saque Aprovado - Investimento de Capital')
            ->view('emails.withdrawal_updated', [
                'message' => "Seu pedido de saque de {$this->amount} foi processado com sucesso.",
                'name' => $this->name,
                'message2' => "Você receberá os fundos na sua conta de saque designada em breve."
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}