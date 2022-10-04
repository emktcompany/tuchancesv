<?php

namespace App\Listeners;

use App\Events\ContactFormCreated;
use App\Notifications\ContactReceived;
use Illuminate\Notifications\AnonymousNotifiable;

class SendContactReceivedNotification
{
    /**
     * Handle the event.
     * @param ContactFormCreated $event
     * @return void
     */
    public function handle(ContactFormCreated $event)
    {
        $to = env('EMAIL_QUOTE_TO', 'info@tuchance.org');

        (new AnonymousNotifiable)
            ->route('mail', $to)
            ->notify(new ContactReceived($event->contact));
    }
}
