<?php

namespace App\Notifications;

use App\TuChance\Models\ContactForm;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactReceived extends Notification
{
    /**
     * @var ContactForm
     */
    protected $contact;

    /**
     * Create a new notification instance.
     *
     * @param ContactForm $contact
     */
    public function __construct(ContactForm $contact)
    {
        $this->contact = $contact;
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
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->salutation('Saludos Cordiales')
            ->subject('Nuevo contacto recibido en tuchance.org')
            ->line("{$this->contact->name} envió la siguiente información")
            ->line("Teléfono: {$this->contact->phone}")
            ->line("Correo: {$this->contact->email}")
            ->line("Mensaje: {$this->contact->message}")
            ->greeting('Hola!');
    }
}
