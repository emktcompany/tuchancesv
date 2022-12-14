<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\BidderCreated'      => [
            'App\Listeners\SendBidderRegistrationNotification',
            'App\Listeners\UpdateBidderCounters',
        ],
        'App\Events\BidderAccepted'     => [
            'App\Listeners\SendBidderAcceptedNotification',
            'App\Listeners\UpdateBidderAcceptedCounters',
        ],
        'App\Events\BidderRejected'     => [
            'App\Listeners\SendBidderRejectedNotification',
        ],
        'App\Events\CandidateCreated'   => [
            'App\Listeners\SendCandidateRegistrationNotification',
            'App\Listeners\UpdateCandidateCounters',
        ],
        'App\Events\EnrollmentAccepted' => [
            'App\Listeners\SendEnrollmentAcceptedNotification',
            'App\Listeners\UpdateEnrollmentAcceptedCounters',
        ],
        'App\Events\EnrollmentCreated'  => [
            'App\Listeners\SendCandidateEnrolledNotification',
            'App\Listeners\UpdateEnrollmentCounters',
        ],
        'App\Events\UserRegistered'     => [
            'App\Listeners\UpdateUserCounters',
        ],
        'App\Events\UserLoggedIn'       => [
            'App\Listeners\SaveNewLogin',
        ],
        'App\Events\OpportunityCreated' => [
            'App\Listeners\UpdateOpportunityCounters',
            'App\Listeners\SendNewOpportunityNotification',
        ],
        'App\Events\UserCreated' => [
            'App\Listeners\SubscribeUserToPrograms',
        ],
        'App\Events\ContactFormCreated' => [
            'App\Listeners\SendContactReceivedNotification',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
