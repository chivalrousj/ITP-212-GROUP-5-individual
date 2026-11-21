<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    // Officers may update events
    public function update(User $user, Event $event): bool
    {
        return $user->hasRole('Officer');
    }

    // Officers may delete an event only when nobody has an attendance row for it
    public function delete(User $user, Event $event): bool
    {
        return $user->hasRole('Officer') && !$event->attendances()->exists();
    }
}
