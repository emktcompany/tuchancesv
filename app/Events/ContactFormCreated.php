<?php

namespace App\Events;

use App\TuChance\Models\ContactForm;

class ContactFormCreated
{
    /**
     * @var ContactForm
     */
    public $contact;

    /**
     * Create a new event instance.
     *
     * @param ContactForm $contact
     */
    public function __construct(ContactForm $contact)
    {
        $this->contact = $contact;
    }
}
