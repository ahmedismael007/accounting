<?php

namespace App\Listeners;

use App\Events\UserUpdated;

class UpdateUser
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserUpdated $event): void
    {
        //
    }
}
