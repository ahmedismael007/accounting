<?php

namespace App\Listeners;

use App\Events\UserDeleted;

class DeleteUser
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
    public function handle(UserDeleted $event): void
    {
        //
    }
}
