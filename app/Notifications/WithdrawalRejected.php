<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WithdrawalRejected extends Notification
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
            ->subject('Saque Rejeitado - Investimento de Capital')
            ->view('emails.withdrawal_updated', [
                'message' => "Seu pedido de saque de {$this->amount} foi rejeitado.",
                'name' => $this->name,
                'message2' => "Seu pedido foi sinalizado e rejeitado pelos nossos sistemas automatizados AML/CFT. Verifique seu painel e comunique-se com seu gerente de conta se acredita que isso é um erro."
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}