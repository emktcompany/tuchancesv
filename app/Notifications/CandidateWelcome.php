<?php

namespace App\Notifications;

class CandidateWelcome extends BaseNotification
{
    /**
     * The folder the messages for this notifications are stored
     * @var string
     */
    protected $translations = 'candidate/created';
}
