<?php

namespace App\Observers;

use App\Models\Contact;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
class UserObserver implements  ShouldHandleEventsAfterCommit
{
    public function __construct(protected UserService $userService)
    {
    }
    /**
     * Handle the Contact "created" event.
     */
    public function created(User $user): void
    {
        activity()
            ->performedOn($user)
            ->causedBy($this->userService->getUserLoggedIn())
            ->withProperties(['attributes' => $user->toArray()])
            ->log('Creó un usuario');
    }

    /**
     * Handle the Contact "updated" event.
     */
    public function updated(User $user): void
    {
         activity()
            ->performedOn($user)
            ->causedBy($this->userService->getUserLoggedIn())
            ->withProperties([
                'old' => $user->getOriginal(),
                'new' => $user->getChanges()
            ])
            ->log('Actualizó un usuario');
    }

    /**
     * Handle the Contact "deleted" event.
     */
    public function deleted(User $user): void
    {
         activity()
            ->performedOn($user)
            ->causedBy($this->userService->getUserLoggedIn())
            ->withProperties(['attributes' => $user->toArray()])
            ->log('Eliminó un usuario');
    }
}
