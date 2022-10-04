<?php

namespace App\Events;

use App\TuChance\Models\User;
use Illuminate\Queue\SerializesModels;

class UserCreated
{
    use SerializesModels;

    /**
     * User created
     * @var \App\TuChance\Models\User
     */
    protected $user;

    /**
     * Create a new event instance.
     * @param  \App\TuChance\Models\User $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the user
     * @return \App\TuChance\Models\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
