<?php

use App\Events\CandidateCreated;

Artisan::command('tuchance:notification:test', function () {
    $user = (new \App\TuChance\Models\User)
        ->role('candidate')
        ->inRandomOrder()
        ->withTrashed()
        ->get()
        ->first();

    $user->email = 'gerardo.gomez.r@gmail.com';

    event(new CandidateCreated($user->candidate));
});
