<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\TuChance\Models\ProgramParticipant;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscribeUserToPrograms
{
    /**
     * Handle the event.
     *
     * @param  UserCreated  $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        $user        = $event->getUser();
        $program_ids = (new ProgramParticipant)
            ->whereEmail('email', $user->email)
            ->pluck('program_id');
        $user->programs()->sync($program_ids->all());
    }
}
